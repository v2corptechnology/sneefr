<?php

namespace Sneefr;

use GuzzleHttp\ClientInterface;
use Illuminate\Support\Collection;

class YelpClient
{
    /**
     * @var \GuzzleHttp\ClientInterface
     */
    private $client;

    /**
     * YelpClient constructor.
     *
     * @param \GuzzleHttp\ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
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
    public function getBusinessesAround(float $latitude, float $longitude, array $options = []) : Collection
    {
        $defaults = ['radius' => 40000, 'limit' => 50, 'offset' => 1, 'categories' => 'shopping'];
        $options = array_merge($defaults, $options);
        $client = $this->client;

        $url = "businesses/search?latitude={$latitude}&longitude={$longitude}&radius={$options['radius']}&limit={$options['limit']}&offset={$options['offset']}&categories={$options['categories']}";

        return cache()->remember($url, 24 * 60 * 60, function () use ($client, $url) {
            return collect(json_decode((string) $client->get($url)->getBody(), true)['businesses']);
        });
    }

}
