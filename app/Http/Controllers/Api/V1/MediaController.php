<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\MediaResource;
use App\Models\MiniApp;
use App\Services\MediaService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    use ApiResponse;

    private MediaService $mediaService;

    public function __construct(MediaService $mediaService)
    {
        $this->mediaService = $mediaService;
    }

    public function upload(Request $request, MiniApp $miniApp)
    {
        $request->validate([
            'file' => ['required', 'file', 'max:10240'],
            'collection' => ['sometimes', 'string', 'in:logo,assets,screenshots'],
        ]);

        $collection = $request->input('collection', 'assets');
        $media = $this->mediaService->upload($miniApp, $request->file('file'), $collection);

        return $this->created(
            new MediaResource($media),
            'File uploaded successfully'
        );
    }

    public function destroy(int $mediaId)
    {
        $this->mediaService->delete($mediaId);

        return $this->success(null, 'Media deleted successfully');
    }
}
