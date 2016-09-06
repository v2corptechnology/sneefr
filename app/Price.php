<?php namespace Sneefr;

class Price
{
    /*
     * Raw format is divided by one to be outputted.
     */
    const FORMAT_RAW = 1;

    /*
     * Readable format is divided by 100 to be outputted.
     */
    const FORMAT_READABLE = 100;

    /**
     * The output format for the price.
     *
     * @var string raw|readable
     */
    public $format;

    /**
     * The price value we are working with.
     *
     * @var int
     */
    public $value;

    /**
     * The amount this price stands for.
     *
     * @var string
     */
    protected $amount;

    /**
     * The currency used with this price.
     * Follows the ISO 4217 currency codes.
     *
     * @var string
     */
    protected $currency;

    /**
     * @var \Sneefr\Delivery
     */
    protected $delivery;

    /**
     * Price constructor.
     *
     * @param int              $amount
     * @param string           $currency
     * @param \Sneefr\Delivery $delivery
     */
    public function __construct(int $amount, string $currency, Delivery $delivery)
    {
        $this->amount = $amount;
        $this->currency = $currency;
        $this->format = self::FORMAT_RAW;
        $this->delivery = $delivery;
    }

    /**
     * Magic method to output the price nicely.
     *
     * @return string
     */
    public function __toString() : string
    {
        return $this->amount / $this->format;
    }

    /**
     * Get the price in raw format (*100).
     *
     * @return \Sneefr\Price
     */
    public function raw() : self
    {
        $this->format = self::FORMAT_RAW;

        return $this;
    }

    /**
     * Get the price in readable format (/100).
     *
     * @return \Sneefr\Price
     */
    public function readable() : self
    {
        $this->format = self::FORMAT_READABLE;

        return $this;
    }

    /**
     * Add the fee to the current amount.
     *
     * @param string $feeName
     *
     * @return \Sneefr\Price
     */
    public function withFee(string $feeName) : self
    {
        $this->amount += $this->delivery->amountFor($feeName);

        return $this;
    }

    /**
     * Get the price currency.
     *
     * @return string
     */
    public function getCurrency() : string
    {
        return $this->currency;
    }
}
