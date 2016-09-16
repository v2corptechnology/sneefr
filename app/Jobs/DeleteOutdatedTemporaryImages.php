<?php namespace Sneefr\Jobs;

use Illuminate\Filesystem\FilesystemManager;
use Log;

class DeleteOutdatedTemporaryImages extends Job
{
    /**
     * @var int $hoursLimit The number of hours after a directory is outdated
     */
    protected $hoursLimit = 2;

    /**
     * @var \Illuminate\Filesystem\FilesystemManager $storage The disk instance used for images.
     */
    private $storage;

    /**
     * Create a new job instance.
     *
     * @param \Illuminate\Filesystem\FilesystemManager $storage
     */
    public function __construct(FilesystemManager $storage)
    {
        $this->storage = $storage->disk('images');
    }

    /**
     * Delete every file in the temp folder that is too old
     */
    public function handle()
    {
        foreach ($this->outdatedFiles() as $file) {
            $this->storage->delete($file);
        }
    }

    /**
     * Grab a list of the outdated files
     *
     * @return array
     */
    public function outdatedFiles()
    {
        $files = $this->storage->allFiles('temp');

        return array_filter(array_map(function ($item) {
            if ($this->isOutdated($this->storage->lastModified($item))) {
                return $item;
            }
        }, $files));
    }

    /**
     * Check if the timestamp is older than 3 days
     *
     * @param int $timestamp
     *
     * @return bool
     */
    public function isOutdated($timestamp)
    {
        return $timestamp < (time() - 60 * 60 * $this->hoursLimit);
    }

}

