<?php namespace Sneefr\Http\Controllers;

use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Sneefr\Models\ActionLog;
use Sneefr\Models\LikeAd;
use Sneefr\Models\User;
use Sneefr\Repositories\Ad\AdRepository;
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
    public function index(AdRepository $adRepository)
    {
        $randomAd = \Sneefr\Models\Ad::orderByRandom()->with(['seller', 'shop'])->take(1)->get()->first();
        $topShops = \Sneefr\Models\Shop::withCount('ads')->orderBy('ads_count', 'desc')->take(3)->get();
        $highlighted = [
            ['class' => 'first', 'parentId' => 1, 'ids' => [2, 3, 4, 5, 6, 7], 'ads' => $adRepository->byCategory(2, 3, 4, 5, 6, 7)],
            ['class' => 'second', 'parentId' => 14, 'ids' => [15, 16, 17, 18], 'ads' => $adRepository->byCategory(15, 16, 17, 18)],
            ['class' => 'third', 'parentId' => 25, 'ids' => [26, 27, 28, 29, 30], 'ads' => $adRepository->byCategory(26, 27, 28, 29, 30)],
            ['class' => 'fourth', 'parentId' => 40, 'ids' => [41, 42, 43, 44, 45], 'ads' => $adRepository->byCategory(41, 42, 43, 44, 45)],
        ];

        if (! $randomAd) {
            return('No ads yet, please <a href="' . route('ad.create') . '">create one</a>');
        }

        return view('pages.home.index', compact('randomAd', 'topShops', 'highlighted'));
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
}
