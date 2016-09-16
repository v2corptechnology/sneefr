<?php namespace Sneefr\Contracts;

/**
 * Allows something to be searched.
 */
interface Searchable
{
    /**
     * Perform the search on this something.
     *
     * @param array $parameters
     *
     * @return \Illuminate\Support\Collection
     */
    public function search(array $parameters);
}
