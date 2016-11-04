<?php

namespace Sneefr;

use GuzzleHttp\Client;
use Illuminate\Support\Collection;

class YelpClient
{
    /**
     * @var Array
     */
    private static $defaultOptions = ['radius' => 40000, 'limit' => 50, 'offset' => 1, 'categories' => 'shopping'];

    /**
     * @return \GuzzleHttp\Client
     *
     * @throws \Exception
     */
    public static function getClient()
    {
        // Get auth key
        $token = self::getToken();

        // Prepare base instance
        return self::getBaseInstance($token);
    }

    /**
     * Get a list of businesses around a specific GPS location.
     *
     * @param float $latitude
     * @param float $longitude
     * @param array $options
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getBusinessesAround(float $latitude, float $longitude, array $options = []) : Collection
    {
        $options = array_merge(self::$defaultOptions, $options);

        $url = "businesses/search?latitude={$latitude}&longitude={$longitude}&radius={$options['radius']}&limit={$options['limit']}&offset={$options['offset']}&categories={$options['categories']}";

        return collect(json_decode((string) self::getClient()->get($url)->getBody(), true)['businesses']);
    }

    /**
     * @return string
     *
     * @throws \Exception
     */
    public static function getToken() : string
    {
        $client = new \GuzzleHttp\Client();

        $credentials = $client->post('https://api.yelp.com/oauth2/token', ['form_params' => self::getRandomToken()]);

        if (200 !== $credentials->getStatusCode()) {
            throw new \Exception('Unable to retrieve yelp credentials');
        }

        return json_decode((string) $credentials->getBody())->access_token;
    }

    public static function getBaseInstance(string $token) : Client
    {
        return new \GuzzleHttp\Client([
            'base_uri' => 'https://api.yelp.com/v3/',
            'headers'  => [
                'Authorization' => 'Bearer ' . $token,
            ],
        ]);
    }

    public static function getRandomToken() : array
    {
        $tokens = [
            '_DiM2JrJeEWvGwE1s6Vtgw' => 't8RPgKGCTEcxJc9nZ1DCfEShrhaH5TNCra5DkBvbqcuZQdj9R7R5cVa3ZW00Xo2J',
            'QVy97YZU-rScX81-DRimQw' => 'KQ81elGyFzemmlO2P2s6apPkBhNYD1KfEwsrcVs6MEYlFYdFo5FxoC55K9SfhFOz',
            'j3bUhrAc67xYXPZhW8GNSA' => 'e9072yQRyiZPkb0GaC04E4Pk3RZQHoB55MfbfT1O2Zy6PPQwBACMnC7DeGZ5DKi0',
            '8g3MCLSQs7yn5VqdZIeInA' => 'xPu1IowGpKCx6VHTgsenqmGNAR3kuvq22DnmWV0EuuoOZpkFzRIqPC2UpsRH4L2K',
            '-sUL_n1AWVV_N6dnbY4TJQ' => 'VG4fZMYvQCeUUs6NbKbqjie0zvscSN7gRqM5gKKR8JUNOCXGAeC8PbRNsqNEPy8R',
        ];

        $randItem = array_rand($tokens);

        $i = array_search($randItem, array_keys($tokens));

        \Log::debug("Using key n° {$i}");

        return [
            'grant_type'    => 'client_credentials',
            'client_id'     => $randItem,
            'client_secret' => $tokens[$randItem],
        ];
    }

}
