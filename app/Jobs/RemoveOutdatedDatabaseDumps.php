<?php namespace Sneefr\Jobs;

use Illuminate\Filesystem\FilesystemManager;

class RemoveOutdatedDatabaseDumps extends Job
{
    /**
     * @var int $hoursLimit The number of hours after a dump is outdated
     */
    protected $hoursLimit = 720; // 24 hours x 30 days

    /**
     * @var \Illuminate\Filesystem\FilesystemManager $storage The disk instance used for dumps.
     */
    private $storage;

    /**
     * Create a new job instance.
     *
     * @param \Illuminate\Filesystem\FilesystemManager $storage
     */
    public function __construct(FilesystemManager $storage)
    {
        $this->storage = $storage->disk('dumps');
    }

    /**
     * Delete every dump that is too old
     */
    public function handle()
    {
        // Don't backup outside of production environments
        if (config('sneefr.APP_ENV') !== 'production') return;
        
        foreach ($this->outdatedDumps() as $dump) {
            $this->storage->delete($dump);
        }
    }

    /**
     * Grab a list of the outdated dumps
     *
     * @return array
     */
    public function outdatedDumps()
    {
        $outDated = [];

        foreach ($this->storage->allFiles() as $dump) {

            $timestamp = $this->storage->lastModified($dump);

            if ($this->isOutdated($timestamp) && !$this->mustBeKept($dump)) {
                $outDated[] = $dump;
            }
        }

        return $outDated;
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

    /**
     * Check if this is a backup we need to keep.
     *
     * @param string $dumpName
     *
     * @return bool
     */
    public function mustBeKept($dumpName)
    {
        // Dumpname example forge-2015-04-28-17-00-02.sql.gz
        $fragments = explode('-', $dumpName);

        // Keep it if it's a backup made at 3 o'clock;
        return $fragments[4] === '03';
    }

}
