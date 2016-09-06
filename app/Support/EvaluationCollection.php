<?php namespace Sneefr\Support;

use Illuminate\Database\Eloquent\Collection;

class EvaluationCollection extends Collection
{
    /**
     * Get latest evaluations.
     *
     * @return \Illuminate\Support\Collection
     */
    public function latest() : Collection
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
     * @return \Illuminate\Support\Collection
     */
    public function positives() : Collection
    {
        return $this->where('value', '1');
    }

    /**
     * Get negative evaluations.
     *
     * @return \Illuminate\Support\Collection
     */
    public function negatives() : Collection
    {
        return $this->where('value', '0');
    }

}
