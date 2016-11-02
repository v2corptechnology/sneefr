<?php

namespace Sneefr\Console\Commands;

use Illuminate\Console\Command;
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
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $yelpClient = new YelpClient($this->getClient());

        $shops = $yelpClient->getBusinessesAround(34.052235, -118.243683, ['limit' => 50, 'offset' => cache()->get('yelp_import_offset', '1')]);

        foreach($shops as $yelp) {

            // Increment offset
            cache()->increment('yelp_import_offset');

            // Do not create twice the shops
            if (Shop::where('slug', $yelp['id'])->count()) {
                continue;
            }

            $shop = Shop::create([
                'user_id' => 1,
                'slug'    => $yelp['id'],
                'data'    => [
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
                ],
            ]);

            $shop->tags()->attach(Tag::whereIn('alias', $yelp['categories'])->pluck('id')->toArray());
        }
    }


    private function getClient()
    {
        $token = cache()->remember('yelp-api-auth-token', 30, function () {

            $client = new \GuzzleHttp\Client();

            $response = $client->post('https://api.yelp.com/oauth2/token', [
                'form_params' => [
                    'grant_type'    => 'client_credentials',
                    'client_id'     => '_DiM2JrJeEWvGwE1s6Vtgw',
                    'client_secret' => 't8RPgKGCTEcxJc9nZ1DCfEShrhaH5TNCra5DkBvbqcuZQdj9R7R5cVa3ZW00Xo2J',
                ],
            ]);

            if (200 !== $response->getStatusCode()) {
                cache()->forget('yelp-api-auth-token');
                cache()->forget('yelp-api-base');

                return;
            }

            return json_decode((string) $response->getBody())->access_token;
        });

        return new \GuzzleHttp\Client([
            'base_uri' => 'https://api.yelp.com/v3/',
            'headers'  => [
                'Authorization' => 'Bearer ' . $token,
            ],
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

        $order = array('address1', 'address2', 'address3', 'city', 'state', 'zip_code', 'country');

        uksort($location, function ($a, $b) use ($order) {
            $pos_a = array_search($a, $order);
            $pos_b = array_search($b, $order);
            return $pos_a > $pos_b;
        });

        return implode(', ', $location);
    }
}
