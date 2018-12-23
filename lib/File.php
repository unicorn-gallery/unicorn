<?php

namespace Mre\Unicorn\lib;

/**
 * File functions
 */
class File
{
    /**
     * Read raw file content.
     *
     * @param string $path
     * @return bool|string
     */
    public static function read($path)
    {
        $content = "";

        // File must exist
        if (!file_exists($path)) {
            return $content;
        }

        // File exists. Open and read.
        $f = fopen($path, 'r');
        if ($f != null) {
            $fileSize = filesize($path);
            if ($fileSize != 0) {
                $content = fread($f, $fileSize);
            }
            fclose($f);
        }
        return $content;
    }

    /**
     * Write raw file content.
     *
     * @param string $path
     * @param string $content
     * @param string $mode
     * @return bool
     */
    public static function write($path, $content, $mode = 'w')
    {
        $f = fopen($path, $mode);
        if ($f != null) {
            fwrite($f, $content);
            fclose($f);
            return true;
        }
        return false;
    }

    /**
     * @param string $file
     * @return bool
     */
    public static function remove($file)
    {
        if (file_exists($file)) {
            return unlink($file);
        }
        return false;
    }

    /**
     * @param $filename
     * @return null|string|string[]
     */
    public static function removeExtension($filename)
    {
        return preg_replace("/\\.[^.\\s]{3,4}$/", "", $filename);
    }

    /**
     * @param string $str
     * @return string
     */
    public static function encode($str)
    {
        return str_replace(" ", "_", $str);
    }

    /**
     * @param string $str
     * @return string
     */
    public static function decode($str)
    {
        return str_replace("_", " ", $str);
    }

    /**
     * Returns a sanitized string, typically for URLs.
     * Original version from chyrp (http://www.chyrp.net)
     *
     * @param $string string to sanitize
     * @param bool $forceLowercase Force the string to lowercase
     * @param bool $anal If set to true, will remove all non-alphanumeric characters
     *
     * @return mixed|null|string|string[]
     */
    public static function sanitize($string, $forceLowercase = false, $anal = false)
    {
        $strip = [
            "~",
            "`",
            "!",
            "@",
            "#",
            "$",
            "%",
            "^",
            "&",
            "*",
            "(",
            ")",
            "=",
            "+",
            "[",
            "{",
            "]",
            "}",
            "\\",
            "|",
            ";",
            ":",
            "\"",
            "'",
            "&#8216;",
            "&#8217;",
            "&#8220;",
            "&#8221;",
            "&#8211;",
            "&#8212;",
            "â€”",
            "â€“",
            ",",
            "<",
            ".",
            ">",
            "/",
            "?"
        ];
        $clean = trim(str_replace($strip, "", strip_tags($string)));
        $clean = preg_replace('/\s+/', "_", $clean);
        $clean = $anal ? preg_replace("/[^a-zA-Z0-9]/", "", $clean) : $clean;

        if ($forceLowercase) {
            if (function_exists('mb_strtolower')) {
                $clean = mb_strtolower($clean, 'UTF-8');
            } else {
                $clean = strtolower($clean);
            }
        }

        return $clean;
    }
}
