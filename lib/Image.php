<?php

namespace Mre\Unicorn\lib;

/**
 * Image functions
 */
class Image
{
    /**
     * Create a thumbnail from an image. Code by Tatu Ulmanen.
     * See http://stackoverflow.com/questions/1855996/crop-image-in-php
     *
     * @param string $originalImage
     * @param string $thumbDir
     * @param int $thumbWidth
     * @param int $thumbHeight
     * @param int $quality
     */
    public static function createThumbnail(
        $originalImage,
        $thumbDir = 'thumbs',
        $thumbWidth = 200,
        $thumbHeight = 200,
        $quality = 80
    ) {
        $extension = strtolower(strrchr($originalImage, '.'));

        switch ($extension) {
            case '.jpg':
            case '.jpeg':
                $image = @imagecreatefromjpeg($originalImage);
                break;
            case '.gif':
                $image = @imagecreatefromgif($originalImage);
                break;
            case '.png':
                $image = @imagecreatefrompng($originalImage);
                break;
            default:
                $image = false;
                break;
        }

        $thumbsPath = dirname($originalImage) . "/" . $thumbDir . "/";
        @mkdir($thumbsPath); // Create thumbnail directory if not existent.

        $filename = $thumbsPath . basename($originalImage);

        $width = imagesx($image);
        $height = imagesy($image);

        $originalAspect = $width / $height;
        $thumbAspect = $thumbWidth / $thumbHeight;

        if ($originalAspect >= $thumbAspect) {
            // If image is wider than thumbnail (in aspect ratio sense)
            $newHeight = $thumbHeight;
            $newWidth = $width / ($height / $thumbHeight);
        } else {
            // If the thumbnail is wider than the image
            $newWidth = $thumbWidth;
            $newHeight = $height / ($width / $thumbWidth);
        }

        $thumb = imagecreatetruecolor($thumbWidth, $thumbHeight);

        // Resize and crop
        imagecopyresampled(
            $thumb,
            $image,
            0 - ($newWidth - $thumbWidth) / 2, // Center the image horizontally
            0 - ($newHeight - $thumbHeight) / 2, // Center the image vertically
            0,
            0,
            $newWidth,
            $newHeight,
            $width,
            $height
        );
        imagejpeg($thumb, $filename, $quality);
    }
}
