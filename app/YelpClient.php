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

        cache()->rememberForever('yelp_shops_results_for_la', function() use ($client, $url) {
            $result = json_decode((string) $client->get($url)->getBody(), true);

            return $result['total'];
        });

        // If offset overlaps results, skip the call
        if ($options['offset'] > cache()->get('yelp_shops_results_for_la')) {
            return collect();
        }

        return collect(json_decode((string) $client->get($url)->getBody(), true)['businesses']);
    }

}
