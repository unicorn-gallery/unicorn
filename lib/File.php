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

  public static function remove($file) {
    if (file_exists($file)) {
      return unlink($file);
    }
    return False;
  }

  public static function remove_extension($filename) {
    return preg_replace("/\\.[^.\\s]{3,4}$/", "", $filename);
  }

  /**
   *
   * Function: sanitize
   * Returns a sanitized string, typically for URLs.
   * Original version from chyrp (http://www.chyrp.net)
   *
   * Parameters:
   *     $string - The string to sanitize.
   *     $force_lowercase - Force the string to lowercase?
   *     $anal - If set to *true*, will remove all non-alphanumeric characters.
   */
  public static function sanitize($string, $force_lowercase = false, $anal = false) {
    $strip = array( "~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "=",
                    "+", "[", "{", "]", "}", "\\", "|", ";", ":", "\"", "'", "&#8216;",
                    "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;",
                    "â€”", "â€“", ",", "<", ">", "/", "?");
      $clean = trim(str_replace($strip, "", strip_tags($string)));
      $clean = preg_replace('/\s+/', "_", $clean);
      $clean = ($anal) ? preg_replace("/[^a-zA-Z0-9]/", "", $clean) : $clean ;
      return ($force_lowercase) ?
          (function_exists('mb_strtolower')) ?
              mb_strtolower($clean, 'UTF-8') :
              strtolower($clean) :
          $clean;
  }

  public static function age($path) {
      if (!file_exists($path)) {
        return -1;
      }
      $fmtime = filemtime($path);
      $currtime = date("U");
      $delta = $currtime - $fmtime;
      return $delta;
  }


}
?>
