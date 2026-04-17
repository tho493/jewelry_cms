<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadMediaRequest;
use App\Models\Media;
use App\Models\Product;
use App\Services\MediaService;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function __construct(private MediaService $mediaService) {}

    /**
     * AJAX: Upload a media file for a product.
     */
    public function upload(UploadMediaRequest $request)
    {
        $product = Product::findOrFail($request->product_id);

        try {
            $media = $this->mediaService->upload($request->file('file'), $product);

            return response()->json([
                'success'       => true,
                'media'         => [
                    'id'            => $media->id,
                    'type'          => $media->type,
                    'url'           => $media->url,
                    'thumbnail_url' => $media->thumbnail_url,
                    'alt_text'      => $media->alt_text,
                    'caption'       => $media->caption,
                    'is_cover'      => $media->is_cover,
                    'sort_order'    => $media->sort_order,
                ],
            ]);
        } catch (\Throwable $e) {
            \Log::error('Media upload failed', [
                'product_id' => $product->id,
                'error'      => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Tải file thất bại: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * AJAX: Delete a media file.
     */
    public function destroy(Media $media)
    {
        $this->mediaService->delete($media);

        return response()->json(['success' => true]);
    }

    /**
     * AJAX: Update Media Data (caption, alt_text, etc)
     */
    public function update(Request $request, Media $media)
    {
        $request->validate([
            'caption' => 'nullable|string|max:255',
        ]);

        $media->update([
            'caption' => $request->caption,
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * AJAX: Set a media as the cover image.
     */
    public function setCover(Media $media)
    {
        $this->mediaService->setCover($media);

        return response()->json(['success' => true]);
    }

    /**
     * AJAX: Reorder media items.
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'integer|exists:media,id',
        ]);

        $this->mediaService->reorder($request->ids);

        return response()->json(['success' => true]);
    }
}
