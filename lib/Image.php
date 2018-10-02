<?php

namespace lib;


// Image functions
class Image {

  /**
   * Create a thumbnail from an image. Code by Tatu Ulmanen.
   * See http://stackoverflow.com/questions/1855996/crop-image-in-php
   */
  public static function create_thumbnail($original_image, $thumb_dir = 'thumbs', $thumb_width = 200, $thumb_height = 200, $quality = 80) {
	  $extension = strtolower(strrchr($original_image, '.'));

	  switch ($extension) {
		  case '.jpg':
		  case '.jpeg':
		      $image = @imagecreatefromjpeg($original_image);
			  break;
		  case '.gif':
			  $image = @imagecreatefromgif($original_image);
			  break;
		  case '.png':
			  $image = @imagecreatefrompng($original_image);
			  break;
		  default:
			  $image = false;
			  break;
	  }

    $thumbs_path = dirname($original_image) . "/" . $thumb_dir . "/";
    @mkdir($thumbs_path); // Create thumbnail directory if not existent.

    $filename =  $thumbs_path . basename($original_image);

    $width = imagesx($image);
    $height = imagesy($image);

    $original_aspect = $width / $height;
    $thumb_aspect = $thumb_width / $thumb_height;

    if ( $original_aspect >= $thumb_aspect )
    {
       // If image is wider than thumbnail (in aspect ratio sense)
       $new_height = $thumb_height;
       $new_width = $width / ($height / $thumb_height);
    } else {
       // If the thumbnail is wider than the image
       $new_width = $thumb_width;
       $new_height = $height / ($width / $thumb_width);
    }

    $thumb = imagecreatetruecolor( $thumb_width, $thumb_height );

    // Resize and crop
    imagecopyresampled($thumb,
                       $image,
                       0 - ($new_width - $thumb_width) / 2, // Center the image horizontally
                       0 - ($new_height - $thumb_height) / 2, // Center the image vertically
                       0, 0,
                       $new_width, $new_height,
                       $width, $height);
    imagejpeg($thumb, $filename, $quality);
  }
}

?>
