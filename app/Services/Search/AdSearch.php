<?php

namespace Sneefr\Services\Search;

use Illuminate\Support\Collection;
use Illuminate\Support\Fluent;
use Sneefr\Contracts\Services\SearchService;
use Sneefr\Models\Ad;

class AdSearch implements SearchService
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

            $results = Ad::search($query, $searchConfig);

        } catch (\Exception $e) {

            return abort(504);
            
        }

        // Transform array into collection of Ad models
        $this->results = collect($results['hits'])->map(function ($hit) {
            $ad = new Ad($hit);
            $ad->title = html_entity_decode($hit['title']);
            $ad->distance = array_get($hit, '_rankingInfo.matchedGeoLocation.distance', 0);
            $ad->setSellerEvaluationRatio($hit['evaluationRatio']);
            return $ad;
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
        $config = [];

        $sortCriterion = $parameters->get('sort');

        $sortOrder = $parameters->get('order', 'desc');

        $isOtherIndex = in_array($sortCriterion, ['condition', 'date', 'evaluation', 'price']);

        // Choose the format to use with the index
        $indexFormat = $isOtherIndex ? 'ads_by_%s_%s' : 'ads';

        // Format the index
        $config['index'] = sprintf($indexFormat, $sortCriterion, $sortOrder);

        // If search by proximity
        if ($sortCriterion == 'proximity') {

            $config['getRankingInfo'] = true;
            
            if (auth()->check() && auth()->user()->getLatitude()) {
                $config['aroundLatLng'] = auth()->user()->getLatitude() . ",".auth()->user()->getLongitude();
            } else {
                $config['aroundLatLngViaIP'] = true;
            }
        }

        // If Search by coordinates around 5 KM
        if($sortCriterion == 'coordinates'){
            $config['aroundLatLng'] = $parameters->get('lat') . ",". $parameters->get('long');
            $config['aroundRadius'] = 5000;
        }

        // If search by category
        if ($parameters->get('categories')) {
            // See https://www.algolia.com/doc/ruby#faceting-parameters
            $categories = json_decode($parameters->get('categories'));

            $formattedCategories = array_map(function ($catgory) {
                return "category_id:{$catgory}";
            }, $categories);

            $config['facets'] = 'categories';
            $config['maxValuesPerFacet'] = '10';
            $config['facetFilters'] = '('.implode($formattedCategories,',').')';
        }

        // Set the maximum amount of hits that will be retrieved.
        $config['hitsPerPage'] = 50;

        return $config;
    }
}
