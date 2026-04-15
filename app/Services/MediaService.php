<?php

namespace App\Services;

use App\Models\Media;
use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class MediaService
{
    const ALLOWED_IMAGE_MIMES = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    const ALLOWED_VIDEO_MIMES = ['video/mp4', 'video/quicktime', 'video/x-msvideo', 'video/webm'];
    const ALLOWED_AUDIO_MIMES = ['audio/mpeg', 'audio/wav', 'audio/mp4', 'audio/x-m4a', 'audio/aac'];

    public function upload(UploadedFile $file, Product $product): Media
    {
        $type     = $this->detectType($file);
        $uuid     = Str::uuid();
        $ext      = $file->getClientOriginalExtension();
        $dir      = "products/{$type}s/{$product->id}";
        $filename = "{$uuid}.{$ext}";
        $path     = "{$dir}/{$filename}";

        // Store original file
        Storage::disk('public')->putFileAs($dir, $file, $filename);

        // Generate thumbnail for images
        $thumbnailPath = null;
        if ($type === 'image') {
            $thumbnailPath = $this->generateThumbnail($file, $dir, $uuid);
        }

        // Check if this is the first media (auto-cover)
        $isCover     = $product->media()->count() === 0 && $type === 'image';
        $sortOrder   = $product->media()->max('sort_order') + 1;

        return Media::create([
            'product_id'     => $product->id,
            'type'           => $type,
            'file_path'      => $path,
            'thumbnail_path' => $thumbnailPath,
            'is_cover'       => $isCover,
            'sort_order'     => $sortOrder,
        ]);
    }

    public function generateThumbnail(UploadedFile $file, string $dir, string $uuid): string
    {
        $thumbDir  = "{$dir}/thumbnails";
        $thumbName = "{$uuid}_thumb.webp";
        $thumbPath = "{$thumbDir}/{$thumbName}";

        Storage::disk('public')->makeDirectory($thumbDir);

        try {
            $image = Image::read($file->getRealPath());
            $image->cover(400, 400);

            $fullThumbPath = Storage::disk('public')->path($thumbPath);
            $image->toWebp(85)->save($fullThumbPath);
        } catch (\Throwable $e) {
            // Thumbnail generation failed — log but don't block the upload
            \Log::warning('Thumbnail generation failed', [
                'file' => $file->getClientOriginalName(),
                'error' => $e->getMessage(),
            ]);
            // Return empty string so no thumbnail will be stored
            return '';
        }

        return $thumbPath;
    }

    public function delete(Media $media): void
    {
        Storage::disk('public')->delete($media->file_path);

        if ($media->thumbnail_path) {
            Storage::disk('public')->delete($media->thumbnail_path);
        }

        $media->delete();
    }

    public function setCover(Media $media): void
    {
        // Unset all covers for this product
        Media::where('product_id', $media->product_id)
            ->update(['is_cover' => false]);

        $media->update(['is_cover' => true]);
    }

    public function reorder(array $orderedIds): void
    {
        foreach ($orderedIds as $index => $id) {
            Media::where('id', $id)->update(['sort_order' => $index]);
        }
    }

    private function detectType(UploadedFile $file): string
    {
        $mime = $file->getMimeType();

        if (in_array($mime, self::ALLOWED_IMAGE_MIMES)) {
            return 'image';
        }

        if (in_array($mime, self::ALLOWED_VIDEO_MIMES)) {
            return 'video';
        }

        if (in_array($mime, self::ALLOWED_AUDIO_MIMES)) {
            return 'audio';
        }

        abort(422, 'Loại file không được hỗ trợ.');
    }
}
