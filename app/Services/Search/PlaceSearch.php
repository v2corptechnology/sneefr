<?php

namespace Sneefr\Services\Search;

use Illuminate\Support\Collection;
use Illuminate\Support\Fluent;
use Sneefr\Contracts\Services\SearchService;
use Sneefr\Models\Place;
use Sneefr\Models\PlaceName;
use Sneefr\Repositories\Place\PlaceRepository;

class PlaceSearch implements SearchService
{
    /**
     * The results found.
     *
     * @var int
     */
    protected $results;

    /**
     * Number of total results found.
     *
     * @var int
     */
    protected $totalResults;

    /**
     * Get the result collection.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getResults() : Collection
    {
        return $this->results;
    }

    /**
     * Get the total number of results found.
     *
     * @return int
     */
    public function getTotal() : int
    {
        return (int) $this->totalResults;
    }

    /**
     * Make the real search.
     *
     * @param string                     $query
     * @param \Illuminate\Support\Fluent $parameters
     */
    public function performSearch(string $query, Fluent $parameters)
    {
        $searchConfig = $this->getSearchParameters($parameters);

        // Perform the search on the index
        try {

            $results = PlaceName::search($query, $searchConfig);

        } catch (\Exception $e) {

            return abort(504);
            
        }

        // Transform array into collection of Ad models
        $placesIds = array_pluck($results['hits'], 'place_id');

        $placeRepository = app(PlaceRepository::class);

        $this->results = $placeRepository->bySequence($placesIds)->values();

        $this->totalResults = $results['nbHits'];
    }

    /**
     * Extract the parameters for this type of search.
     *
     * @param \Illuminate\Support\Fluent $parameters
     *
     * @return array
     */
    protected function getSearchParameters(Fluent $parameters) : array
    {
        return $config = [
            'index' => 'place_names',
            // Set the maximum amount of hits that will be retrieved.
            'hitsPerPage' => 50,
        ];
    }
}
