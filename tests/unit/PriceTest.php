<?php

use Sneefr\Price;

class PriceTest extends TestCase
{
    public function test_can_be_built_from_cents()
    {
        $price = Price::fromCents(1000);

        $this->assertInstanceOf(Price::class, $price);
        $this->assertEquals(10, $price->get());
    }

    public function test_accepts_price_with_cents()
    {
        $price = Price::fromCents(1984);

        $this->assertEquals(19.84, $price->get());
    }

    public function test_price_can_be_retrieved_in_cents()
    {
        $price = Price::fromCents(1984);

        $this->assertEquals(1984, $price->cents());
    }

    public function test_it_gets_price_for_multiple_items()
    {
        $price = Price::fromCents(1000);

        $this->assertEquals($price->for(5)->get(), 50);
    }

    public function test_it_gets_price_with_tax()
    {
        $price = Price::fromCents(1000);

        $this->assertEquals(10.9, $price->tax()->get());
        $this->assertEquals(12.1, $price->tax(21)->get());
    }

    public function test_it_gets_price_with_delivery()
    {
        $price = Price::fromCents(1000);

        $this->assertEquals(35, $price->delivery(2500)->get());
    }

    public function test_it_formats_price_to_a_currency()
    {
        $price = Price::fromCents(1050);

        $this->assertEquals('€10.50', $price->formatted('eur'));
        $this->assertEquals('$10.50', $price->formatted('usd'));
    }
}
