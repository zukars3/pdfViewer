<?php


namespace App;


use Imagick;

class ThumbnailGenerationService
{
    public function generateThumbnail(string $source, string $target): bool
    {
        if (file_exists($source) && !is_dir($source)) {
            if (mime_content_type($source) != 'application/pdf') {
                return false;
            }

            $page = 0;

            $image = new Imagick();
            $image->setResolution(180, 180);
            $image->readImage($source . "[$page]");
            $image->setImageFormat('jpeg');
            $image->setImageCompression(imagick::COMPRESSION_JPEG);
            $image->setImageCompressionQuality(0);
            $image->setImageBackgroundColor('white');
            $image->setImageAlphaChannel(11);
            $image->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);

            if (!is_dir(dirname($target))) {
                mkdir(dirname($target), 0777, true);
            }

            $image->writeimage($target);
            $image->clear();
            $image->destroy();

            return true;
        }
        return false;
    }
}
