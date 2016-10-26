<?php

namespace Sneefr\Support;

use Illuminate\Database\Eloquent\Collection;

class EvaluationCollection extends Collection
{
    /**
     * Get latest evaluations.
     *
     * @return \Sneefr\Support\EvaluationCollection
     */
    public function latest() : self
    {
         return $this->sortByDesc('created_at');
    }
      /**
     * Get ratio of positive vs. negative evaluations
     *
     * @return int
     */
    public function ratio() : int
    {
        if (! $this->count()) {
            return -1;
        }

        return 100 * ($this->positives()->count() / $this->count());
    }

    /**
     * Get positive evaluations.
     *
     * @return \Sneefr\Support\EvaluationCollection
     */
    public function positives() : self
    {
        return $this->where('is_positive', true);
    }

    /**
     * Get negative evaluations.
     *
     *
     * @return \Sneefr\Support\EvaluationCollection
     */
    public function negatives() : self
    {
        return $this->where('is_positive', false);
    }
}
