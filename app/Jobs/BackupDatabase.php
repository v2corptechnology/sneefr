<?php

namespace Sneefr\Jobs;

class BackupDatabase extends Job
{
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Don't backup outside of production environments
        if (config('sneefr.APP_ENV') !== 'production') return;

        // Generate the file name
        // ex: forge-2015-04-28-23-00-01.sql.gz
        $filename = $this->getConfigOf('database') . "-" . date("Y-m-d-H-i-s") . '.sql.gz';

        // Prepare the command to run
        $command = "mysqldump -h ". $this->getConfigOf('host').
                   " -u " . $this->getConfigOf('username') .
                   " -p" . $this->getConfigOf('password') .
                   " " . $this->getConfigOf('database') .
                   " | gzip -9 > " . storage_path($filename);

        // Execute the command
        system($command);

        $this->saveDumpsToCloud();
    }

    /**
     * Copy the dump files in "the cloud".
     */
    public function saveDumpsToCloud()
    {
        foreach ($this->getDumps() as $dump) {

            $dir = $this->extractYearMonthDirectory($dump);

            $uploaded = $this->upload($dir, $dump);

            // Remove the dump file from local storage
            if ($uploaded !== false) {
                \File::delete($dump);
            }
        }
    }

    /**
     * Get only dump files.
     *
     * @return array
     */
    public function getDumps()
    {
        // Grab files in the storage
        $files = \File::files(storage_path());

        // Filter only the dumps/.gz
        $dumps = array_filter($files, function($file) {
            return \File::mimeType($file) === 'application/x-gzip';
        });

        return $dumps;
    }

    /**
     * Extract the filename information
     * ex: 2015-04
     *
     * @param string $file
     *
     * @return string
     */
    protected function extractYearMonthDirectory(string $file)
    {
        $names = explode('-', $file);

        return $names[1] . '-' . $names[2] . '/';
    }

    /**
     * Save the file on the disk
     * ex: 2015-04/forge-2015-04-28-23-00-01.sql.gz
     *
     * @param string $dir
     * @param string $dump
     *
     * @return mixed
     */
    protected function upload(string $dir, string $dump)
    {
        $cloudName = $dir . \File::name($dump) .'.'. \File::extension($dump);

        return \Storage::disk('dumps')->put($cloudName, \File::get($dump));
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
}
