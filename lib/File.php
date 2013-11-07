<?php

namespace lib;

// File functions
class File {

  /**
  * Read raw file content.
  */
  public static function read($path) {
    $content = "";

    // File must exist
    if(!file_exists($path))
      return $content;

    // File exists. Open and read.
    $f = fopen($path, 'r');
    if($f != null) {
      $filesize = filesize($path);
      if($filesize != 0)
        $content = fread($f, $filesize);
      fclose($f);
    }
    return $content;
  }

  /**
  * Write raw file content.
  */
  public static function write($path, $content, $mode='w') {
    $f = fopen($path, $mode);
    if($f != null) {
      fwrite($f, $content);
      fclose($f);
      return true;
    }
    return false;
  }

  public static function remove_extension($filename) {
    return preg_replace("/\\.[^.\\s]{3,4}$/", "", $filename);
  }
}
?>
