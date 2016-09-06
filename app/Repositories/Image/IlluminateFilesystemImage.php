<?php namespace Sneefr\Repositories\Image;

use Exception;
use UnexpectedValueException;
use Sneefr\Contracts\Repositories\Image as ImageRepositoryContract;
use Sneefr\Repositories\Image\AdImage;
use Illuminate\Contracts\Filesystem\Factory as FilesystemFactory;
use Illuminate\Contracts\Config\Repository as ConfigRepository;

/**
 * Repository to manage images, using the Illuminate
 * filesystem component for its implementation.
 */
class IlluminateFilesystemImage implements ImageRepositoryContract
{
    /**
     * The name of the disk to use.
     *
     * @var string
     */
    protected $diskName = 'images';

    /**
     * The disk instance used for images.
     *
     * @var \Illuminate\Contracts\Filesystem\Filesystem
     */
    protected $disk;

    /**
     * The configuration of the application.
     *
     * @var \Illuminate\Contracts\Config\Repository
     */
    protected $appConfig;

    /**
     * The root URL to the repository.
     *
     * @var string
     */
    protected $rootUrl;

    /**
     * The set of available thumb dimensions.
     *
     * @var array
     */
    protected $sizes = [
        '47x59',
        '85x68',
        '128x128',
        '175x133',
        '300x200',
        '488x400',
        '848x565',
        '1200x630',
    ];

    /**
     * Creates a new image repository.
     *
     * @param \Illuminate\Contracts\Filesystem\Factory  $filesystem
     * @param \Illuminate\Contracts\Config\Repository   $config
     */
    public function __construct(FilesystemFactory $filesystem, ConfigRepository $config)
    {
        $this->disk      = $filesystem->disk($this->diskName);
        $this->appConfig = $config;
    }

    /**
     * Get the images associated with a given ad.
     *
     * @param  int           $adId   Identifier of the ad
     * @param  string|array  $sizes  If set, get only images of that/these size(s)
     *
     * @return array  A multidimensional array of AdImage objects
     */
    public function getForAd($adId, $sizes = null)
    {
        $images = [];

        foreach ($this->filterSizes($sizes) as $size) {
            $images[$size] = $this->getImages("thumbs/{$size}/{$adId}");
        }

        return $images;
    }

    /**
     * Remove an image from the repository.
     *
     * @param  \Sneefr\Repositories\Images\AdImage  $image  The image to remove
     *
     * @return bool  Whether or not the operation succeeded
     */
    public function remove(AdImage $image)
    {
        $path = $this->removeRootUrl($image->url);

        return $this->disk->delete($path);
    }

    /**
     * Remove images associated with a given ad.
     *
     * @param  int           $adId   Identifier of the ad
     * @param  string|array  $sizes  If set, remove only images of that/these size(s)
     */
    public function removeForAd($adId, $sizes = null)
    {
        foreach ($this->filterSizes($sizes) as $size) {
            $this->disk->deleteDirectory("thumbs/{$size}/{$adId}");
        }
    }

    /**
     * Get the root URL to the repository.
     *
     * @return string
     */
    public function getRootUrl()
    {
        if (is_null($this->rootUrl)) {
            $this->rootUrl = $this->findRootUrl();
        }

        return $this->rootUrl;
    }

    /**
     * Find the root URL of the repository depending
     * on the configured filesystem driver.
     *
     * @return string
     *
     * @throws \Exception if trying to use an unsupported driver.
     */
    protected function findRootUrl()
    {
        $diskConfig = $this->appConfig->get('filesystems.disks.'.$this->diskName);

        switch ($driver = $diskConfig['driver']) {
            case 'local':
                return app('url')->asset('images');
            case 's3':
                return sprintf(
                    'https://s3.%s.amazonaws.com/%s',
                    $diskConfig['region'],
                    $diskConfig['bucket']
                );
        }

        throw new Exception("‘{$driver}’ filesystem driver is not supported");
    }

    /**
     * Remove the root URL of the repository from a given absolute URL.
     *
     * @param  string  $url
     *
     * @return string
     */
    protected function removeRootUrl($url)
    {
        return str_replace($this->getRootUrl(), '', $url);
    }

    /**
     * Filter sizes and return only valid ones.
     *
     * @param  string|array|null  $sizes
     *
     * @return array
     *
     * @throws \UnexpectedValueException if a size is not in the set of allowed sizes.
     */
    protected function filterSizes($sizes)
    {
        // If no size was provided, return all the available ones.
        if (is_null($sizes)) {
            return $this->sizes;
        }

        // If something else than an array was
        // passed, grab all of the arguments.
        if (!is_array($sizes)) {
            $sizes = func_get_args();
        }

        // Check the validity of the provided sizes.
        foreach ($sizes as $size) {
            if (!in_array($size, $this->sizes)) {
                throw new UnexpectedValueException("{$size} is not an allowed thumbnail size");
            }
        }

        return $sizes;
    }

    /**
     * Get images from a given directory.
     *
     * @param  string  $directory
     *
     * @return array   An array of zero or more AdImage objects.
     */
    protected function getImages($directory)
    {
        $paths = $this->disk->files($directory);

        // Filter out invalid paths.
        $paths = array_filter($paths, [$this, 'isValidImagePath']);

        // Convert paths to AdImage instances.
        return array_map(function ($path) {
            return new AdImage($this->getRootUrl().'/'.$path);
        }, $paths);
    }

    /**
     * Check if a given path is valid.
     *
     * @param  string  $path
     *
     * @return bool
     */
    protected function isValidImagePath($path)
    {
        // Check that the path does not point to a ‘dot file’.
        return (bool) (strpos(basename($path), '.') > 0);
    }
}
