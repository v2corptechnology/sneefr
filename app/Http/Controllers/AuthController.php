<?php namespace Sneefr\Http\Controllers;

use Hashids\Hashids;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Sneefr\Models\ActionLog;
use Sneefr\Models\Ad;
use Sneefr\Models\Category;
use Sneefr\Models\Shop;
use Sneefr\Models\User;
use Sneefr\Services\FacebookConnector;

class AuthController extends Controller
{
    /**
     * @var \Sneefr\Services\FacebookConnector
     */
    private $connector;

    public function __construct()
    {
        // TODO: implement proper application binding
        $this->connector = app(FacebookConnector::class);
    }

    /**
     * @param \Sneefr\Repositories\Ad\AdRepository $adRepository
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $categories = Category::parent()->with('childrens')->get();
        $categories->child = null;
        $categories->parent = null;
        $category = Category::find($request->get('category'));

        if($category) {
            $categories->child = $category->id;
            $categories->parent = $category->child_of ?: $category->id;
        }

        if($category) {
            $shopsByCategory = Category::whereIn('id', $category->getChildsIds())->with('shops')->get()->take(6)->pluck('shops')->collapse()->unique('shop');
        }else {
            $shopsByCategory = Shop::with('evaluations')->take(6)->get();
        }

        $bestSellers = Ad::take(6)->get();
        $topShops = Shop::withCount('ads')->with(['evaluations','ads' => function ($query) { $query->take(3);}])->orderBy('ads_count', 'desc')->take(4)->get();
        
        return view('home.index', compact('shopsByCategory', 'topShops', 'bestSellers', 'categories'));
    }

    /**
     * Log the user in via a third-party authentication provider.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login()
    {
        session(['url.intended-to' => url()->previous() ]);
        return redirect($this->connector->getAuthenticationUrl());
    }

    /**
     * Log the user in again via a third-party authentication provider.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reauthenticate()
    {
        return redirect($this->connector->getReAuthenticationUrl());
    }

    /**
     * Perform a ‘shop’ login via a third-party authentication provider.
     *
     * @param \Illuminate\Http\Request  $request
     * @param \Illuminate\Session\Store $sessionStore
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function loginShops(Request $request, Store $sessionStore)
    {
        $sessionStore->put('plan', $request->segment(2, 'monthly'));

        $this->connector->setAuthenticationType('shop');

        return redirect($this->connector->getAuthenticationUrl());
    }

    /**
     * Handle authentication calls coming back from the auth provider.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     * @event \Sneefr\Events\UserRegistered
     */
    public function handleProviderCallback(Request $request)
    {
        // Authentication failed or not access to the required scopes.
        if (!$this->connector->passes()) {

            return view('errors.missing_social_network_scopes')
                ->with('missingScopes', $this->connector->getMissingScopes());
        }

        // The person is properly authenticated and has given
        // us access to all the scopes we required. Great!
        $user = User::register($this->connector->getProfile());

        if(!$user) {
            return redirect('/login')->withError(trans('login.facebook_email_exist'));
        }

        // Log in the user
        \Auth::login($user, true);

        // If we performed a ‘shop’ type of login
        if ($this->connector->isShop()) {
            return redirect()->route('shops.create');
        }
        
        // Redirect back where intended.
        if (strpos(session()->get('url.intended'), '/login')  && !strpos(session()->get('url.intended'), 'pusherAuth')) {
            return redirect()->intended('?first_time');
        }

        return redirect()->to(session()->get('url.intended-to'));
    }

    /**
     * Generate a token to use a private channel with this user identifier.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Hashids\Hashids         $hashids
     *
     * @return string
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function pusherAuth(Request $request, Hashids $hashids)
    {
        $socket = $request->input('socket_id');
        $channel = $request->input('channel_name');

        $userHash = explode('-', $channel);
        $userId = $hashids->decode(array_pop($userHash))[0];

        if ($userId == $request->user()->id()) {

            $auth = \LaravelPusher::socket_auth($channel, $socket);

            return $auth;
        }

        abort(401);
    }

    /**
     * Log the user out of the application.
     *
     * @return Response
     */
    public function logout()
    {
        // Log the logout event.
        if (auth()->check()) {
            ActionLog::create([
                'type'    => ActionLog::USER_LOGOUT,
                'user_id' => auth()->id()
            ]);
        }

        \Auth::logout();
        \Session::flush();

        return redirect('/login');
    }

    /**
     * Activate account using data from email link
     *
     * @param $key
     * @param Encrypter $encrypter
     * @return \Illuminate\Http\RedirectResponse
     */
    public function Activate($key, Encrypter $encrypter)
    {
        // Decrypt the verification key
        $data = $encrypter->decrypt($key);

        // Basic check
        if ( !empty($data) &&  isset($data['id']) && isset($data['email']) )
        {
            // Get the person based on this user identifier
            $user = User::findOrFail($data['id']);

            if($user->getEmail() == $data['email'] && !$user->isVerified())
            {
                $user->email_verified = true;
                $user->verified = true;
                $user->save();

                return redirect()->route('home')->with('success', trans('feedback.email_activation_success'));
            }
        }

        return redirect()->route('home')->with('error', trans('feedback.email_activation_error'));
    }
}
