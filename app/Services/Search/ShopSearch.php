<?php namespace Sneefr\Services\Search;

use Illuminate\Support\Collection;
use Illuminate\Support\Fluent;
use Sneefr\Contracts\Services\SearchService;
use Sneefr\Models\Shop;

class ShopSearch implements SearchService
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

            $results = Shop::search($query, $searchConfig);

        } catch (\Exception $e) {

            return abort(504);
        }


        // Transform array into collection of Shop models
        $this->results = collect($results['hits'])->map(function ($hit) {

            $shop = new Shop($hit);

            return $shop;
        });

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
            'index' => 'shops',
            // Set the maximum amount of hits that will be retrieved.
            'hitsPerPage' => 50,
        ];
    }
}
