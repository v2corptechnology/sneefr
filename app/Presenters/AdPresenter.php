<?php namespace Sneefr\Presenters;

use Laracodes\Presenter\Presenter;
use NumberFormatter;

class AdPresenter extends Presenter
{
    public function condition() : string
    {
        return trans('condition.alt_names.' . $this->model->getConditionId());
    }

    public function title()
    {
        return $this->model->getTitle();
    }

    public function distance($format = '%s')
    {
        $distance = $this->model->getDistance();

        if (!$distance) {
            return null;
        }

        $km  = floor($distance / 1000); // amount of "full" kilometers

        if ($km) {
            return sprintf($format, "{$km} km");
        }

        $rkm = $distance % 1000; // rest

        return sprintf($format, "{$rkm} m");
    }

    public function price($amount = null)
    {
        $amount = $amount ?? $this->model->price()->readable();

        return $this->formatAmount((string) $amount);
    }

    public function negotiatedPrice()
    {
        return $this->formatAmount((string) $this->model->negotiatedPrice()->readable());
    }

    public function description()
    {
        return nl2br(strip_tags($this->model->description));
    }

    public function simpleDescription()
    {
        $stripped = strip_tags(nl2br($this->model->description));
        return trim( str_replace( [PHP_EOL, "\r"], '', $stripped ) );
    }

    public function evaluationRatio()
    {
        return (int) $this->model->getSellerEvaluationRatio() * 100;
    }

    public function getFees()
    {
        $fees = $this->model->delivery->getFees();
        foreach ($fees as $key => $value) {
            $fees[$key] = $value/ 100;
        }

        return $fees;
    }

    protected function formatAmount(float $amount) : string
    {
        $formatter = new NumberFormatter(trans('common.locale_name'),  NumberFormatter::CURRENCY);

        $price =  $formatter->formatCurrency($amount, trans('common.currency'));

        $symbol = $formatter->getSymbol(NumberFormatter::CURRENCY_SYMBOL);

        return str_replace($symbol, "<sup>{$symbol}</sup>", $price);
    }
}
