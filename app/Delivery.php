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
        $allowedDeliveries = $this->fees;

        return $allowedDeliveries;
    }

    public function getCurrency() : string
    {
        return $this->currency;
    }

    public function amountFor(string $fee) : float
    {
        return (float) $this->fees[$fee];
    }
}
