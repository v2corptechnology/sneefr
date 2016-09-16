<?php namespace Sneefr\Http\Controllers;

use Illuminate\Contracts\Filesystem\Factory;
use Sneefr\Http\Requests\ImageUploadRequest;
use Sneefr\Models\Ad;
use Sneefr\Services\Image;

class ImagesController extends Controller
{
    /**
     * The disk instance used for images.
     *
     * @var \Illuminate\Contracts\Filesystem\Filesystem
     */
    protected $disk;

    /**
     * @param \Illuminate\Contracts\Filesystem\Factory $filesystemFactory
     */
    public function __construct(Factory $filesystemFactory)
    {
        $this->disk = $filesystemFactory->disk('images');
    }

    /**
     * Store a new image and attach it to Ad.
     *
     * @param                                          $ad
     * @param \Sneefr\Http\Requests\ImageUploadRequest $request
     * @param \Sneefr\Services\Image                   $imageService
     *
     * @return \Illuminate\Http\Response
     */
    public function store($ad, ImageUploadRequest $request, Image $imageService)
    {
        $ad = Ad::find(explode('-', $ad)[0]);

        // Is the user allowed to edit this ad ?
        $this->authorize('update', $ad);

        // Random image name
        $name = $imageService::generateHash($request->file('file'));

        // Pat for uploading
        $path = "originals/" . $ad->getId() . "/" . $name;

        // Standardized image
        $image = $imageService::standardize($request->file('file'));

        // Move the file
        if (! $this->disk->put($path, $image)) {
            return response()->json(['status' => 'error'], 400);
        }

        // Save the image name in ad data
        $names = $ad->images;
        array_push($names, $name);
        $ad->images = $names;
        $ad->save();

        return response()->json([
            'status'     => 'success',
            'file'       => $name,
            'delete_url' => route('ads.images.destroy', [$ad, $name]),
        ]);
    }

    /**
     * Remove the specified image from storage.
     *
     * @param string $ad
     * @param string $imageName
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $ad, string $imageName)
    {
        $ad = Ad::find(explode('-', $ad)[0]);

        // Is the user allowed to edit this ad ?
        $this->authorize('update', $ad);

        // Get the path to the image
        $path = "originals" . '/' . $ad->getId() . '/' . $imageName;

        // Delete the file
        if (! $this->disk->delete($path)) {
            return response()->json(['status' => 'error'], 400);
        }

        // Remove this image from ad data
        $ad->images = array_values(array_filter($ad->imageNames(), function ($name) use ($imageName) {
            return $name != $imageName;
        }));

        // Save changes
        $ad->save();

        return response()->json(['status' => 'success'], 201);
    }
}
