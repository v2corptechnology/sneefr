<?php namespace Sneefr\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class ClearAlgoliaIndex extends Command
{
    /**
     * The models available to indexation.
     *
     * @var array
     */
    public $models = ['Ad', 'Shop', 'PlaceName', 'User'];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'algolia:clear
                            {model? : The class name of the model to clear. ex: User}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear Algolia\'s index (reindex)';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->getModelsToReindex()->each(function ($model) {
            $this->line("Reindexing {$model}");
            $model::reindex();
        });

        $this->info("Reindex completed, please wait for Algolia to finish the job.");
    }

    /**
     * Get the models to reindex.
     *
     * @return \Illuminate\Support\Collection
     */
    private function getModelsToReindex() : Collection
    {
        $models = $this->argument('model')
            ? [$this->argument('model')]
            : $this->models;

        return collect($models)->map(function ($model) {
            return "\\Sneefr\\Models\\{$model}";
        });
    }
}
