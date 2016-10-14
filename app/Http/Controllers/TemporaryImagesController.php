<?php

namespace Sneefr\Http\Controllers;

use Illuminate\Contracts\Filesystem\Factory;
use Sneefr\Http\Requests\ImageUploadRequest;
use Sneefr\Services\Image;

class TemporaryImagesController extends Controller
{
    /**
     * @var \Illuminate\Contracts\Filesystem\Filesystem
     */
    public $disk;

    /**
     * TemporaryImagesController constructor.
     *
     * @param \Illuminate\Contracts\Filesystem\Factory $fileSystemFactory
     */
    public function __construct(Factory $fileSystemFactory)
    {
        $this->disk = $fileSystemFactory->disk('images');
    }

    /**
     * Store a new image in temp storage.
     *
     * @param \Sneefr\Http\Requests\ImageUploadRequest $request
     * @param \Sneefr\Services\Image                   $imageService
     *
     * @return \Illuminate\Http\Response
     */
    public function store(ImageUploadRequest $request, Image $imageService)
    {
        // Random image name
        $name = $imageService::generateHash($request->file('file'));

        // Pat for uploading
        $path = "temp/" . auth()->id() . "/" . $name;

        // Standardized image
        $image = $imageService::standardize($request->file('file'));

        // Move the file
        if (! $this->disk->put($path, $image)) {
            return response()->json(['status' => 'error'], 400);
        }

        return response()->json([
            'status'     => 'success',
            'file'       => $name,
            'delete_url' => route('temporaryImages.destroy', $name),
        ]);
    }

    /**
     * Delete a temporary image from user's folder.
     *
     * @param String $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(String $id)
    {
        $imagePath = "temp/" . auth()->id() . "/" . $id;

        if ($this->disk->delete($imagePath)) {
            return response()->json(['status' => 'success'], 201);
        }

        return response()->json(['status' => 'error'], 400);
    }
}
