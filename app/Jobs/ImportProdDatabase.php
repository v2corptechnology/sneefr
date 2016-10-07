<?php

namespace Sneefr\Jobs;

class ImportProdDatabase extends Job
{
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Don't import backup outside of staging environment
        if (config('sneefr.APP_ENV') !== 'local') return;

        // Get a copy of the latest export
        $this->getLatestExport();

        // Prepare the command to run
        $command = "gunzip -c /home/forge/staging-v2.com/storage/app/import.sql.gz| ".
            "mysql --user={$this->getConfigOf('username')} " .
            "--password={$this->getConfigOf('password')} " .
            "--database={$this->getConfigOf('database')}";


        // Execute the command
        system($command);

        \Log::info('Production database was imported successfully');
    }

    /**
     * Get the configuration value of the current connection.
     *
     * @param string $key
     *
     * @return string
     */
    protected function getConfigOf(string $key)
    {
       return config('database.connections.'.config('database.default').'.'.$key);
    }

    /**
     * Get the name of the latest export.
     *
     * @return string
     */
    protected function getLatestExport()
    {
        $files = \Storage::disk('dumps')->allFiles();

        $exportName = array_pop($files);

        \Storage::disk('local')->put('import.sql.gz', \Storage::disk('dumps')->get($exportName));
    }
}
