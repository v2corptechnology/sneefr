<?php namespace Sneefr\Contracts;

interface BillingInterface
{
    /**
     * Perform a charge.
     *
     * @param array $data
     */
    public function charge(array $data);
}
