<?php

namespace Sneefr\Presenters;

use Laracodes\Presenter\Presenter;
use NumberFormatter;

class AdPresenter extends Presenter
{
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
}
