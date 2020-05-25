<?php

namespace App;

use Illuminate\Support\Facades\Storage;
use Imagick;

class ThumbnailGenerationService
{
    public function generate(Document $document, string $hashName): string
    {
        $thumbnailPath = 'files/images/' . $hashName . 'jpg';

        $source = Storage::disk('public')->path($document->path);
        $target = Storage::disk('public')->path($thumbnailPath);

        $image = new Imagick();
        $image->setResolution(180, 180);
        $image->readImage($source . "[0]");
        $image->setImageFormat('jpeg');
        $image->setImageCompression(imagick::COMPRESSION_JPEG);
        $image->setImageCompressionQuality(0);
        $image->setImageBackgroundColor('white');
        $image->setImageAlphaChannel(11);
        $image->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);

        $image->writeimage($target);
        $image->clear();
        $image->destroy();

        return $thumbnailPath;
    }
}
