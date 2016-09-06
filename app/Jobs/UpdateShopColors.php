<?php namespace Sneefr\Jobs;

use Illuminate\Contracts\Filesystem\Factory;
use Img;
use League\ColorExtractor\Color;
use League\ColorExtractor\ColorExtractor;
use League\ColorExtractor\Palette;
use Sneefr\Models\Shop;

/**
 * Determine the best colors to use in combination with the cover image.
 *
 * @package Sneefr\Jobs
 */
class UpdateShopColors extends Job
{
    /**
     * @var \Sneefr\Models\Shop
     */
    private $shop;

    /**
     * Create a new job instance.
     *
     * @param \Sneefr\Models\Shop $shop
     */
    public function __construct(Shop $shop)
    {
        $this->shop = $shop;
    }

    /**
     * Execute the job.
     *
     * @param \Illuminate\Contracts\Filesystem\Factory $factory
     *
     * @return array
     */
    public function handle(Factory $factory)
    {
        $slug = $this->shop->getRouteKey();
        $coverName = $this->shop->getCoverName();

        $url = $factory->disk('images')->url("shops/{$slug}/{$coverName}");

        if (strpos($url, '/storage/') !== false) {
            $url = str_replace('/storage/', '/images/', $url);
            $url = asset($url);
        }

        # /!\ Hard-coded URL.
        $url = 'https://eazkmue.cloudimg.io/bound/200x200/tjpgq75/' . $url;

        // Determine the ‘most representative’ color of the image.
        $palette = Palette::fromFilename($url);
        $mostRepresentative = (new ColorExtractor($palette))->extract(1);

        $colors = [
            'background_color' => Color::fromIntToHex($mostRepresentative[0]),
            'font_color'       => Img::getContrastYIQ($mostRepresentative[0]),
        ];

        $this->shop->update(['data' => collect($this->shop->data)->merge($colors)]);
    }
}
