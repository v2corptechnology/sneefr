<?php

namespace Sneefr\Providers;

use Carbon\Carbon;
use Collective\Html\HtmlServiceProvider;
use Illuminate\Support\ServiceProvider;

class MacroServiceProvider extends HtmlServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // Macros must be loaded after the HTMLServiceProvider's
        // register method is called.
        parent::register();

        // Regiter macros
        \HTML::macro('time', function ($date) {
            
            $date = \Carbon\Carbon::parse($date);

            return '<time class="timeago" datetime="' .
                   $date->toIso8601String() .
                   '" title="' .
                   $date->format('d/m/Y H:i') .
                   '">' . $date->formatLocalized('%d %B %Y') . '</time>';
        });

        \HTML::macro('profilePicture', function ($facebookId, $alt, $dimensions = 40, array $classes = []) {
            if (is_array($dimensions)) {
                $width = $dimensions[0];
                $height = isset($dimensions[1]) ? $dimensions[1] : $width;
            } else {
                $width = $dimensions;
                $height = $dimensions;
            }

            $normal = \Img::avatar($facebookId, [$width, $height]);
            $double = \Img::avatar($facebookId, [$width, $height, 2]);

            $classes = implode(' ', $classes);

            return '<img class="'.$classes.'" src="'.$normal.'" srcset="'.$double.' 2x" width="'.$width.'" height="'.$height.'" alt="'.$alt.'">';
        });
    }
}
