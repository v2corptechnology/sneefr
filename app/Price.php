<?php namespace Sneefr;

use NumberFormatter;

class Price
{
    /**
     * @var int
     */
    private $cents;

    /**
     * @var string
     */
    private $currency;

    /**
     * @var int
     */
    private $deliveryCost = 0;

    /**
     * @var int
     */
    private $quantity = 1;

    /**
     * @var int
     */
    private $taxPercentage = 0;

    /**
     * Price constructor.
     *
     * @param int    $cents
     * @param string $currency
     */
    public function __construct(int $cents, string $currency = 'USD')
    {
        $this->cents = $cents;
        $this->currency = $currency;
    }

    /**
     * Named constructor to get a price starting with cents
     *
     * @param int $cents
     *
     * @return \Sneefr\Price
     */
    public static function fromCents(int $cents) : Price
    {
        return new Price($cents);
    }

    /**
     * Get the current price amount.
     *
     * @return float
     */
    public function get() : float
    {
        $totalAmount = $this->cents * $this->quantity;

        $tax = ($totalAmount * $this->taxPercentage) / 100;

        return ($totalAmount + $this->deliveryCost + $tax) / 100;
    }

    /**
     * Get the current price amount in cents.
     *
     * @return int
     */
    public function cents() : int
    {
        return $this->get() * 100;
    }

    /**
     * Set the delivery cost.
     *
     * @param int $deliveryCostCents
     *
     * @return \Sneefr\Price
     */
    public function delivery(int $deliveryCostCents = 0) : self
    {
        $this->deliveryCost = $deliveryCostCents;

        return $this;
    }

    /**
     * Set the quantity of items for calculating the price.
     *
     * @param int $quantity
     *
     * @return \Sneefr\Price
     */
    public function for (int $quantity = 1) : self
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Format the number according to currency rules
     *
     * @param string $currency
     *
     * @return string
     */
    public function formatted(string $currency = null) : string
    {
        $currency = $currency ?? $this->currency ?? trans('common.currency');

        return $this->formatForCurrency($currency);
    }

    /**
     * Set the tax percentage to apply.
     *
     * @param int $taxPercentage
     *
     * @return \Sneefr\Price
     */
    public function tax(int $taxPercentage = 9) : self
    {
        $this->taxPercentage = $taxPercentage;

        return $this;
    }

    /**
     * Get the amount this tax costs.
     *
     * @param int $taxPercentage
     *
     * @return \Sneefr\Price
     */
    public function taxOnly(int $taxPercentage = 9) : Price
    {
        $taxFee = (($this->cents * $this->quantity) * $taxPercentage) / 100;

        return new Price($taxFee, $this->currency);
    }

    /**
     * Format an amount into a currency-aware string.
     *
     * @param string $currency
     *
     * @return string
     */
    private function formatForCurrency(string $currency) : string
    {
        $formatter = new NumberFormatter(trans('common.locale_name'), NumberFormatter::CURRENCY);

        return $formatter->formatCurrency($this->get(), $currency);
    }
}
