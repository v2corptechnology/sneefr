<?php

namespace Sneefr\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Sneefr\Models\Shop;
use Sneefr\Models\Tag;
use Sneefr\YelpClient;

class ImportYelpShop extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'yelp:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import yelp shops into our database';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $index = cache()->get('yelp_import_index', env('YELP_BASE_INDEX', 0));

        $matrix = $this->buildMatrix(33.5, 34.4, -118.4, -117.6);

        $returnedShops = $this->getAllShopsAround($matrix[$index][0], $matrix[$index][1]);

        $shops = $this->deduplicate($returnedShops);

        $this->insertShops($shops);

        $this->addTags($shops);

        cache()->increment('yelp_import_index');

        \Log::info("Imported index {$index} {$matrix[$index][0]},{$matrix[$index][1]}", [
            'found' => $returnedShops->count(),
            'inserted' => $shops->count(),
            'duplicates' => $returnedShops->count() - $shops->count()
        ]);
    }

    /**
     * @param array $location
     *
     * @return string
     */
    private function makeLocationFromArray(array $location) : string
    {
        $location = array_filter($location);

        $order = ['address1', 'address2', 'address3', 'city', 'state', 'zip_code', 'country'];

        uksort($location, function ($a, $b) use ($order) {
            $pos_a = array_search($a, $order);
            $pos_b = array_search($b, $order);

            return $pos_a > $pos_b;
        });

        return implode(', ', $location);
    }

    /**
     * @param float $minY
     * @param float $maxY
     * @param float $minX
     * @param float $maxX
     * @param float $gap // 0.005 ~ Roughly 500m
     *
     * @return array
     */
    private function buildMatrix(float $minY, float $maxY, float $minX, float $maxX, float $gap = 0.005) : array
    {
        // Key for the stored instance of the matrix
        $key = 'matrix_for' . $minY . '_' . $maxY . '_' . $minX . '_' . $maxX . '_' . $gap;

        // If key is not present in cache, create the matrix
        if (! cache()->has($key)) {

            for ($lat = $minY; $lat <= $maxY; $lat = round((float) $lat += $gap, 5)) {
                for ($lon = $minX; $lon <= $maxX; $lon = round((float) $lon += $gap, 5)) {
                    $matrix[] = [$lat, $lon];
                }
            }

            cache()->put($key, $matrix, 60 * 24);
        }

        // Return the matrix
        return cache()->get($key);
    }

    /**
     * Remove shops already existing in storage.
     *
     * @param \Illuminate\Support\Collection $shops
     *
     * @return \Illuminate\Support\Collection
     */
    private function deduplicate(Collection $shops) : Collection
    {
        $existingShops = Shop::whereIn('slug', $shops->pluck('id'))->get()->pluck('slug');

        return $shops->reject(function ($shop) use ($existingShops) {
            return $existingShops->contains($shop['id']);
        })->unique('id');
    }

    /**
     * Create the tags for inserted shops.
     *
     * @param \Illuminate\Support\Collection $shops
     */
    private function addTags(Collection $shops)
    {
        $shops = Shop::whereIn('slug', $shops->pluck('id'))->get();

        foreach ($shops as $shop) {
            $shop->tags()->attach(Tag::whereIn('alias', $shop->data['yelp_data']['categories'])->pluck('id')->toArray());
        }
    }

    /**
     * Insert the shops in one batch.
     *
     * @param $shops
     */
    private function insertShops($shops)
    {
        $shopsData = [];

        foreach ($shops as $yelp) {
            $shopsData[] = [
                'user_id' => 1,
                'slug'    => $yelp['id'],
                'data'    => json_encode([
                    'name'             => $yelp['name'],
                    'cover'            => $yelp['image_url'],
                    'logo'             => $yelp['image_url'],
                    'terms'            => 0,
                    'latitude'         => $yelp['coordinates']['latitude'],
                    'longitude'        => $yelp['coordinates']['longitude'],
                    'location'         => $this->makeLocationFromArray($yelp['location']),
                    'font_color'       => '#000000',
                    'background_color' => '#FFFFFF',
                    'description'      => $yelp['phone'] ?? null,
                    'yelp_data'        => $yelp,
                ]),
            ];
        }

        Shop::insert($shopsData);
    }

    private function getAllShopsAround($latitude, $longitude) : Collection
    {
        $results = YelpClient::getBusinessesAround($latitude, $longitude);

        $this->info('Shops found ' . $results['total']);

        $shops = collect($results['businesses']);

        while($shops->count() < $results['total']) {

            $this->info('Doing offset ' . $shops->count());

            $offsetResults = YelpClient::getBusinessesAround($latitude, $longitude, ['offset' => $shops->count()]);

            $shops = $shops->merge(collect($offsetResults['businesses']));
        }

        return $shops;
    }
}
