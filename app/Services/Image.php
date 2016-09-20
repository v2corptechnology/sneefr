<?php namespace Sneefr\Services;

use InvalidArgumentException;
use Sneefr\Models\Ad as AdModel;
use Sneefr\Models\Shop;
use Sneefr\Models\User as UserModel;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Retrieve images from a third-party service.
 */
class Image
{
    /**
     * Base URL to the third-party service to get images from.
     *
     * @var string
     */
    protected $baseUrl;

    /**
     * The list of available path patterns.
     *
     * @var array
     */
    protected $patterns = [
        'ad'         => 'bound/{dimensions}/q75/_originals_/{name}',
        'ad_cropped' => 'crop/{dimensions}/q75/_originals_/{name}',
        'avatar'     => 'crop/{dimensions}/q75/http://s3.amazonaws.com/sneefr.prod.ad-images/avatar/{name}',
        'cover'      => 'crop/{dimensions}/q75/_shops_/{name}',
        'logo'       => 'crop/{dimensions}/q75/_shops_/{name}',
    ];

    /**
     * Class constructor
     *
     * @return self
     */
    public function __construct()
    {
        $this->baseUrl = config('sneefr.keys.CLOUD_IMAGE_ROOT_URL');

        // While on staging, use staging url
        if (app()->environment() == 'staging') {
            $this->patterns = array_map(function ($pattern) {
                return str_replace(['originals', 'shops'], ['originals-staging', 'shops-staging'], $pattern);
            }, $this->patterns);
        }
    }

    /**
     * Get a unique name for this file.
     *
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return string
     */
    public static function generateHash(UploadedFile $file) : string
    {
        return md5(time() . rand()) . '.' . $file->getClientOriginalExtension();
    }

    /**
     * Every image must stick to constraints.
     *
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return string
     */
    public static function standardize(UploadedFile $file) : string
    {
        return \Image::make($file)
            ->orientate()
            ->resize(1800, 1800, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->encode('jpg', 100)
            ->__toString();
    }

    /**
     * Get the avatar URL for a given person.
     *
     * @param  mixed  $person      An integer, Person entity or User model
     * @param  mixed  $dimensions  Dimensions of the image
     *
     * @return string
     */
    public function avatar($avatar, $dimensions = 40) : string
    {
        return $this->getImageUrl('avatar', $avatar, $dimensions);
    }

    /**
     * Alias of static::thumbnail().
     *
     * @param  mixed  $ad          An integer, Ad entity or Ad model
     * @param  mixed  $photo       The name of the photo to get
     * @param  mixed  $dimensions  Dimensions of the image
     *
     * @return string
     */
    public function thumb($ad, $photo, $dimensions = 120) : string
    {
        return static::thumbnail($ad, $photo, $dimensions);
    }

    /**
     * Get a thumbnail URL for a given ad photo.
     *
     * @param  mixed  $ad          An integer, Ad entity or Ad model
     * @param  mixed  $photo       The name of the photo to get
     * @param  mixed  $dimensions  Dimensions of the image
     *
     * @return string
     */
    public function thumbnail($ad, $photo, $dimensions = 120) : string
    {
        $name = $this->getIdentifier($ad).'/'.$photo;

        return $this->getImageUrl('ad', $name, $dimensions);
    }

    /**
     * Get a cropped thumbnail URL for a given ad photo.
     *
     * @param  mixed  $ad          An integer, Ad entity or Ad model
     * @param  mixed  $photo       The name of the photo to get
     * @param  mixed  $dimensions  Dimensions of the image
     *
     * @return string
     */
    public function cropped($ad, $photo, $dimensions = 120) : string
    {
        $name = $this->getIdentifier($ad).'/'.$photo;

        return $this->getImageUrl('ad_cropped', $name, $dimensions);
    }

    /**
     * Get a crop for a given shop cover.
     *
     * @param \Sneefr\Models\Shop $shop       The shop we want the cover
     * @param  mixed              $dimensions Dimensions of the image
     *
     * @return string
     */
    public function cover(Shop $shop, $dimensions = 120) : string
    {
        $name = $shop->getRouteKey().'/'.$shop->getCoverName();

        return $this->getImageUrl('cover', $name, $dimensions);
    }

    /**
     * Get a crop for a given shop logo.
     *
     * @param \Sneefr\Models\Shop $shop       The shop we want the cover
     * @param  mixed              $dimensions Dimensions of the image
     *
     * @return string
     */
    public function logo(Shop $shop, $dimensions = 120) : string
    {
        $name = $shop->getRouteKey().'/'.$shop->getLogoName();

        return $this->getImageUrl('logo', $name, $dimensions);
    }

    /**
     * Get the most contrasted color given a base one.
     *
     * @param string $hexadecimalColor (must be 6 digits color)
     *
     * @return string
     */
    public function getContrastYIQ(string $hexadecimalColor) : string
    {
        $r = hexdec(substr($hexadecimalColor, 0, 2));
        $g = hexdec(substr($hexadecimalColor, 2, 2));
        $b = hexdec(substr($hexadecimalColor, 4, 2));

        $yiq = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;

        return ($yiq >= 128) ? '#000000' : '#FFFFFF';
    }

    /**
     * Extract an identifier from a given piece of data.
     *
     * @param  mixed  $argument  An integer, entity or Eloquent model
     *
     * @throws \InvalidArgumentException if no identifier can be found.
     *
     * @return string
     */
    protected function getIdentifier($argument) : string
    {
        if (is_numeric($argument)) {
            $identifier = $argument;
        } elseif ($argument instanceof UserModel) {
            $identifier = $argument->getId();
        } elseif ($argument instanceof AdModel) {
            $identifier = $argument->id;
        } else {
            throw new InvalidArgumentException("Cannot find identifier of [$argument]");
        }

        return (string) $identifier;
    }

    /**
     * Get a normalized set of dimensions.
     *
     * If only one number is given, that same number will be used for both the
     * width and the height. If three numbers are provided, the third will by
     * used as a multiplier for the width and the height.
     * In addition to arrays of numbers, the method also accepts a string
     * notation, using `x` to separate width and height and using an `@`
     * to append a scale ratio. Examples: '25x40', '25x40@2x'.
     *
     * @param  mixed  $dimensions  This argument can have different types:
     *                               - an array containing two integers
     *                               - a string following the `\d+x\d+(@\dx)?` pattern
     *                               - a single integer, that will be used for both dimensions
     *
     * @throws \InvalidArgumentException if dimensions cannot be parsed.
     *
     * @return array  An array containing two integers: the width and the height
     */
    protected function parseDimensions($dimensions) : array
    {
        // If needed, we first turn the argument into an array.
        if (!is_array($dimensions)) {
            $dimensions = preg_split('#[x@]#', $dimensions);
        }

        // If no height is defined, we’ll use the width.
        // This gives us square dimensions.
        $width = (int) $dimensions[0];
        $height = isset($dimensions[1]) ? (int) $dimensions[1] : $width;

        // Are we requesting a high resolution image? If so, we simulate
        // it by multiplying the dimensions by the ratio.
        if (isset($dimensions[2]) && $dimensions[2] > 1) {
            $width = (int) ($width * $dimensions[2]);
            $height = (int) ($height * $dimensions[2]);
        }

        // Ensure that we are dealing with integers that are greater than zero.
        if ($width <= 0 || $height <= 0) {
            throw new InvalidArgumentException("Cannot parse dimensions");
        }

        return [$width, $height];
    }

    /**
     * Get a URL for a given type, picture and set of dimensions
     *
     * @param  string  $type        The type of path that is wanted
     * @param  string  $name        Name of the image
     * @param  mixed   $dimensions  Dimensions of the image
     *
     * @return string
     */
    protected function getImageUrl($type, $name, $dimensions) : string
    {
        $dimensions = $this->parseDimensions($dimensions);

        $path = $this->getPath($type, $name, $dimensions);

        return $this->baseUrl.'/'.$path;
    }

    /**
     * Get a path of given type, filled with the provided data.
     *
     * @param  string  $type        The type of path that is wanted
     * @param  string  $name        Name of the image
     * @param  array   $dimensions  Dimensions of the image
     *
     * @return string
     */
    protected function getPath($type, $name, array $dimensions) : string
    {
        $pattern = $this->patterns[$type];

        $tokens = [
            '{dimensions}' => $dimensions[0].'x'.$dimensions[1],
            '{width}'      => $dimensions[0],
            '{height}'     => $dimensions[1],
            '{name}'       => $name,
        ];

        return str_replace(array_keys($tokens), array_values($tokens), $pattern);
    }
}
