<?php namespace Sneefr\Services;

use App;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Fluent;
use Illuminate\Support\Fluent as DataBag;

/**
 * Prepare the query and call the right services.
 */
class SearchService
{
    /**
     * A data container to store parameters.
     *
     * @var \Illuminate\Support\Fluent
     */
    protected $parameters;

    /**
     * The type of item we are searching for.
     *
     * @var string
     */
    protected $type;

    /**
     * The search performed.
     *
     * @var \Sneefr\Contracts\Services\SearchService
     */
    protected $search;

    /**
     * Get the type of this search.
     *
     * @param string $type
     *
     * @return string
     *
     * @throws \Exception
     */
    public function for (string $type = 'ad')
    {
        $className = ucfirst(strtolower($type)) . 'Search';

        $searchClass = "\\Sneefr\\Services\\Search\\{$className}";

        if (!class_exists($searchClass)) {
            throw new Exception('Cannot identify search service [' . $searchClass . ']');
        }

        $instance = (new static);

        $instance->search = new $searchClass();

        $instance->type = $type;

        return $instance;
    }

    /**
     * Set the settings of this search.
     *
     * @param array $parameters
     *
     * @return $this
     */
    public function with(array $parameters)
    {
        $this->parameters = new DataBag($parameters);

        $this->search->performSearch($this->getQuery(), $this->getParameters());

        return $this;
    }

    /**
     * Number of items found.
     *
     * @return int
     */
    public function count() : int
    {
        return $this->search->getTotal();
    }

    /**
     * Results found.
     *
     * @return \Illuminate\Support\Collection
     */
    public function get() : Collection
    {
        return $this->search->getResults();
    }

    /**
     * Get the term searched.
     *
     * @return string
     */
    public function getQuery() : string
    {
        return (string) $this->parameters->get('q');
    }

    /**
     * Get the parameters passed to the search.
     *
     * @return \Illuminate\Support\Fluent
     */
    public function getParameters() : Fluent
    {
        return $this->parameters;
    }
}
