<?php namespace Sneefr\Console\Commands;

use Illuminate\Console\Command;
use Sneefr\Models\Ad;
use Sneefr\Models\PlaceName;
use Sneefr\Models\Shop;
use Sneefr\Models\User;

class InitAlgoliaIndices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'algolia:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prepare the indices Algolia is using';

    /**
     * Special attributes for a specific index.
     *
     * @var array
     */
    private $indices = [
        Ad::class        => [
            'attributesForFacetting' => ["categories"],
        ],
        PlaceName::class => [],
        Shop::class      => [],
        User::class      => [],
    ];

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach ($this->indices as $model => $settings) {

            $model::setSettings($settings);

            $this->info('The model "' . $model . '" is ready for indexing');

        }
    }
}
