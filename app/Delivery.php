<?php namespace Sneefr;

class Delivery
{
    /**
     * @var array
     */
    public $fees;

    /**
     * @var string
     */
    public $currency;

    /**
     * Delivery constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->fees = $data['fees'] ?? [];
        $this->currency = $data['currency'] ?? [];
    }

    /**
     * Can this ad be delivered.
     *
     * @param String $to
     *
     * @return bool
     */
    public function isDeliverable(String $to = null) : bool
    {
        if (is_null($to)) {
            return (bool) count($this->getFees());
        }

        return array_key_exists($to, $this->fees);
    }

    /**
     * Can this ad be picked at the shop.
     *
     * @return bool
     */
    public function isPickable() : bool
    {
        return $this->isDeliverable('pick');
    }

    public function getFees() : array
    {
        $fees = $this->fees;
        foreach ($fees as $key => $value) {
            $fees[$key] = (int) $value;
        }

        return $fees;
    }

    public function getCurrency() : string
    {
        return $this->currency;
    }

    public function amountFor(string $fee = null) : float
    {
        if (! $fee) {
            return 0;
        }

        return (float) $this->fees[$fee];
    }
}
