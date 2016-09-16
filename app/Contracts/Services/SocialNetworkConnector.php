<?php namespace Sneefr\Contracts\Services;

/**
 * Contract for connectors to social networks
 */
interface SocialNetworkConnector
{
    /**
     * Get the URL to an authentication page
     * on the target social network.
     *
     * @return string
     */
    public function getAuthenticationUrl();
}
