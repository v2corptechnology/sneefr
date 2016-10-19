<?php

if ( ! function_exists('strip_email')) {
    /**
     * Hide emails in text.
     *
     * @param  string $text
     *
     * @return string
     */
    function strip_email($text)
    {
        return preg_replace('/[^@\s]*@[^@\s]*\.[^@\s]*/', trans('ad.masked_text'), $text);
    }
}

if ( ! function_exists('strip_phone')) {
    /**
     * (Try to) hide phone number in text.
     *
     * @param  string $text
     *
     * @return string
     */
    function strip_phone($text)
    {
        return preg_replace('/\+?[0-9][0-9()\.\- ]{6,20}[0-9]/', trans('ad.masked_text'), $text);
    }
}

if ( ! function_exists('strip_for_display')) {
    /**
     * Perform both email and phone number replacements.
     *
     * @param  string $text
     *
     * @return string
     */
    function strip_for_display($text)
    {
        return strip_phone(strip_email($text));
    }
}

if ( ! function_exists('change_url_param')) {
    /**
     * Replace one or many url parameters.
     *
     * @param array $parameters
     * @param array $changes
     *
     * @return array
     */
    function change_url_param(array $parameters, array $changes)
    {
        foreach ($changes as $change => $value) {
            $paramExists = array_key_exists($change, $parameters);

            if ((is_null($value) && $paramExists)) {
                unset($parameters[$change]);
            } else {
                $parameters = array_replace($parameters, [$change => $value]);
            }
        }

        return $parameters;
    }
}

if ( ! function_exists('setActive')) {
    /**
     * Return the active class when current route matches the name.
     *
     * @param string|array $routeName
     * @param string $activeClass
     *
     * @return string
     */
    function setActive($routeName, $activeClass = 'active')
    {
        if (is_string($routeName)) $routeName = [$routeName];

        return in_array(Route::currentRouteName(), $routeName) ? $activeClass : '';
    }
}

if ( ! function_exists('base64Image')) {
    /**
     * Transforms the given image into binary data.
     *
     * @param string $filename
     *
     * @return string
     *
     * @throws \Exception
     */
    function base64Image($filename)
    {
        if ($filename) {
            if (is_file($filename)) {

                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime = finfo_file($finfo, $filename);finfo_close($finfo);

                $binary = fread(fopen($filename, "r"), filesize($filename));

                return 'data:' . $mime . ';base64,' . base64_encode($binary);
            }
        }
    }
}

if ( ! function_exists('vincentyGreatCircleDistance')) {
    /**
     * Calculates the great-circle distance between two points, with
     * the Vincenty formula.
     *
     * @param float $latitudeFrom  Latitude of start point in [deg decimal]
     * @param float $longitudeFrom Longitude of start point in [deg decimal]
     * @param float $latitudeTo    Latitude of target point in [deg decimal]
     * @param float $longitudeTo   Longitude of target point in [deg decimal]
     * @param int   $earthRadius   Mean earth radius in [m]
     *
     * @return float Distance between points in [m] (same as earthRadius)
     */
    function vincentyGreatCircleDistance(
        $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
    {
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $lonDelta = $lonTo - $lonFrom;
        $a = pow(cos($latTo) * sin($lonDelta), 2) +
            pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
        $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

        $angle = atan2(sqrt($a), $b);

        return $angle * $earthRadius;
    }
}
