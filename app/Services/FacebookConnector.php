<?php namespace Sneefr\Services;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Session\Store as SessionStore;
use Sneefr\Contracts\Services\SocialNetworkConnector as SocialNetworkConnectorContract;

/**
 * Provide the ability to login via Facebook.
 */
class FacebookConnector implements SocialNetworkConnectorContract
{
    /**
     * The scopes the connector has to require.
     *
     * @var array
     */
    protected $scopes = ['email', 'user_birthday', 'user_friends'];

    /**
     * Optional scopes that the connector will also request access to.
     *
     * @var array
     */
    protected $optionalScopes = [];

    /**
     * All the scopes that have been granted access to.
     *
     * @var array
     */
    protected $grantedScopes;

    /**
     * All the scopes that have not been granted access to.
     *
     * @var array
     */
    protected $deniedScopes;

    /**
     * The base URL to make calls to the Graph API.
     *
     * @var string
     */
    protected $rootGraphUrl = 'https://graph.facebook.com/v2.3/';

    /**
     * The type of authentication to perform.
     *
     * This notion is used internally by the application to differentiate
     * between types of logins: for regular users, shop owners, etc.
     *
     * @var string
     */
    protected $authenticationType = 'user';

    /**
     * Cached Facebook access token used to query the API.
     *
     * @var string
     */
    protected $token;

    /**
     * Whether or not the authentication response
     * has been handled by the connector.
     *
     * @var bool
     */
    protected $hasHandledCallback = false;

    /**
     * Whether or not the person has been authenticated.
     *
     * @var bool
     */
    protected $isAuthenticated;

    /**
     * Cached Facebook profile data corresponding
     * to the connected person.
     *
     * @var array
     */
    protected $profile;

    /**
     * An HTTP client.
     *
     * @var \Guzzle\Http\Client
     */
    protected $client;

    /**
     * A Request instance.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Session store handling session data.
     *
     * @var \Illuminate\Session\Store
     */
    protected $sessionStore;

    /**
     * Instantiates the connector.
     *
     * @param \Guzzle\Http\Client       $client
     * @param \Illuminate\Http\Request  $request
     * @param \Illuminate\Session\Store $store
     */
    public function __construct(
        Client $client,
        Request $request,
        SessionStore $store
    ) {
        $this->client       = $client;
        $this->request      = $request;
        $this->sessionStore = $store;

        // If we are handling a callback for a ‘shop’ type of login,
        // we need to make the connector aware of this.
        if ($request->url() === route('auth.handle.shop')) {
            $this->setAuthenticationType('shop');
        }


    }

    /**
     * Get the type of authentication to perform.
     *
     * @return string
     */
    public function getAuthenticationType()
    {
        return $this->authenticationType;
    }

    /**
     * Set the type of authentication to perform.
     *
     * @param string $type
     *
     * @throws \Exception
     */
    public function setAuthenticationType($type = 'user')
    {
        if (!in_array($type, ['user', 'shop'])) {
            throw new Exception("'$type' is not a valid authentication type");
        }

        $this->authenticationType = $type;
    }

    /**
     * Check if this connector is done for a shop.
     *
     * @return bool
     */
    public function isShop() : bool
    {
        return $this->getAuthenticationType() === 'shop';
    }

    /**
     * Get the URL to an authentication page
     * on the target social network.
     *
     * @return string
     */
    public function getAuthenticationUrl()
    {
        return $this->makeAuthenticationUrl();
    }

    /**
     * Get the URL to a reauthentication page
     * on the target social network.
     *
     * @return string
     */
    public function getReAuthenticationUrl()
    {
        return $this->makeAuthenticationUrl(true);
    }

    /**
     * Generate a URL to an authentication page
     * on the target social network.
     *
     * @return string
     */
    protected function makeAuthenticationUrl($isReauthentication = false)
    {
        $parameters = [
            'client_id'     => config('sneefr.keys.FACEBOOK_CLIENT_ID'),
            'redirect_uri'  => route('auth.handle.'.$this->authenticationType),
            'state'         => $this->sessionStore->getToken(),
            'response_type' => ['code', 'granted_scopes'],
            'scope'         => implode(',', $this->getAllScopes()),
        ];

        if ($isReauthentication) {
            // Require only the list of missing required scopes.
            $scopes = $this->sessionStore->get('facebook_connector-missing_scopes');
            $parameters['scope'] = implode(',', $scopes);
            $parameters['auth_type'] = 'rerequest';
        }

        $queryString = http_build_query($parameters, null, '&');

        return 'https://www.facebook.com/dialog/oauth?'.$queryString;
    }

    /**
     * Handle the authentication response coming from Facebook.
     */
    public function handleCallback()
    {
        // CSRF protection.
        /*if (!$this->hasValidState()) {
            \Log::error('State token missmatch', ['session' => $this->sessionStore->getToken(), 'request' =>  $this->request->get('state')]);
            throw new Exception('State token does not match CSRF token');
        }*/

        $this->hasHandledCallback = true;

        // Get the lists of scopes the person has granted and/or denied.
        $this->grantedScopes = explode(',', $this->request->get('granted_scopes'));
        $this->deniedScopes  = explode(',', $this->request->get('denied_scopes'));

        // Check whether the person has canceled the authentication request.
        if ($this->authenticationWasCanceled()) {

            $this->isAuthenticated = false;

            $this->grantedScopes = [];
            $this->deniedScopes  = $this->getAllScopes();
        } else {
            // Get a user access token for the Facebook API.
            $this->getToken();
        }

        // Save the list of missing scopes in the session.
        $this->sessionStore->put(
            'facebook_connector-missing_scopes',
            $this->getMissingScopes()
        );
    }

    /**
     * Set the token for graph requests.
     *
     * @param string $token
     */
    public function setToken(string $token)
    {
        $this->token = $token;
    }

    /**
     * Check if this connector is ok to continue with.
     *
     * @return bool
     */
    public function passes() : bool
    {
        return $this->isAuthenticated() && $this->hasRequiredScopes();
    }

    /**
     * Check if the person is logged in on the target social network.
     *
     * @return bool
     */
    public function isAuthenticated()
    {
        if (isset($this->isAuthenticated)) {
            return $this->isAuthenticated;
        }

        if (!$this->hasHandledCallback) {
            $this->handleCallback();
        }

        return $this->isAuthenticated = (bool) $this->getToken();
    }

    /**
     * Get an access token for the social network.
     *
     * @return string
     */
    public function getAccessToken()
    {
        if (!$this->hasHandledCallback) {
            $this->handleCallback();
        }

        return $this->getToken();
    }

    /**
     * Get profile data of the connected person.
     *
     * @return array
     */
    public function getProfile()
    {
        $graphData = $this->makeGraphRequest('me');
        $graphData['given_name'] = $graphData['first_name'];
        $graphData['surname'] = $graphData['last_name'];

        $extraData = ['access_token' => $this->getAccessToken()];

        return $this->profile = array_merge($graphData, $extraData);
    }

    /**
     * Get friends of the connected person.
     *
     * This returns an array of friend full names,
     * keyed by social network identifiers.
     *
     * @return array
     */
    public function getFriends()
    {
        $results = $this->makeGraphRequest(
            'me/friends',
            // Overwrite the default limit.
            ['limit' => 250]
        );

        return collect($results['data'])->pluck('name', 'id')->all();
    }

    /**
     * Check if the connector has access to all of the required scopes.
     *
     * @return bool
     */
    public function hasRequiredScopes()
    {
        return (count($this->getMissingScopes()) === 0);
    }

    /**
     * Get the list of required scopes the connector
     * has been denied access to.
     *
     * @return array
     */
    public function getMissingScopes()
    {
        if (!$this->hasHandledCallback) {
            $this->handleCallback();
        }

        return array_intersect($this->scopes, $this->deniedScopes);
    }

    /**
     * Determine if the session and input CSRF tokens match.
     *
     * @return bool
     */
    protected function hasValidState()
    {
        return hash_equals(
            $this->sessionStore->getToken(),
            $this->request->get('state')
        );
    }

    /**
     * Check whether the person has canceled
     * the authentication request.
     *
     * @return bool
     */
    protected function authenticationWasCanceled()
    {
        return (
            $this->request->has('error') &&
            $this->request->get('error_reason') === 'user_denied'
        );
    }

    /**
     * Get a user access token for the Facebook API.
     *
     * @return string
     */
    protected function getToken()
    {
        if (isset($this->token)) {
            return $this->token;
        }

        if (!$this->request->has('code')) {
            return false;
        }

        $parameters = [
            'client_id'     => config('sneefr.keys.FACEBOOK_CLIENT_ID'),
            'client_secret' => config('sneefr.keys.FACEBOOK_CLIENT_SECRET'),
            'redirect_uri'  => route('auth.handle.'.$this->authenticationType),
            'code'          => $this->request->get('code'),
        ];

        $json = $this->makeGraphRequest('oauth/access_token', $parameters);

        return $this->token = $json['access_token'];
    }

    /**
     * Make a request to Facebook’s Graph API.
     *
     * @param  string  $endpoint    The target API endpoint
     * @param  array   $parameters  Optional parameters
     * @param  string  $method      The HTTP method to use
     *
     * @return array
     */
    protected function makeGraphRequest($endpoint, $parameters = [], $method = 'GET')
    {
        // If we don’t have a valid token and aren’t trying
        // to get one, then we abort the request.
        if (!isset($this->token) && $endpoint !== 'oauth/access_token') {
            return;
        }

        $url = $this->rootGraphUrl.ltrim($endpoint, '/');

        $query = array_merge(
            $this->getBaseGraphRequestParameters($endpoint),
            $parameters
        );

        $response = $this->sendHttpRequest($method, $url, $query);

        $data = json_decode($response->getBody()->getContents(), true);

        return $data;
    }

    /**
     * Get the query string parameters to include in every Graph request.
     *
     * @param  string  $endpoint  The target API endpoint
     *
     * @return array
     */
    protected function getBaseGraphRequestParameters($endpoint = null)
    {
        // If the request’s goal is to obtain a token, we of course
        // cannot include a token in its query string.
        if ($endpoint === 'oauth/access_token') {
            return [];
        }

        return [
            'access_token'    => $this->token,
            'appsecret_proof' => $this->getAppSecretProof(),
        ];
    }

    /**
     * Send an HTTP request using an HTTP client.
     *
     * @param  string  $method  The HTTP method to use
     * @param  string  $url     The target URL
     * @param  array   $query   Optional query string parameters
     *
     * @return \Guzzle\Http\Message\RequestInterface
     */
    protected function sendHttpRequest($method, $url, $query)
    {
        $request =  $this->client->request($method, $url, ['query' => $query]);

        return $request;
    }

    /**
     * Generate an app secret proof for Graph requests.
     *
     * @return string
     */
    protected function getAppSecretProof()
    {
        // Make this on the fly in order not to expose it through a property.
        return hash_hmac('sha256', $this->token, config('sneefr.keys.FACEBOOK_CLIENT_SECRET'));
    }

    /**
     * Get the list of both required and optional scopes.
     *
     * @return array
     */
    protected function getAllScopes()
    {
        return array_merge($this->scopes, $this->optionalScopes);
    }
}
