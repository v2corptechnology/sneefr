<?php

namespace Sneefr\Contracts\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Fluent;

/**
 * Contract for third-party search services.
 */
interface SearchService
{
    public function performSearch(string $query, Fluent $parameters);

    public function getResults() : Collection;

    public function getTotal() : int;
}
