<?php

namespace lib;

/**
 * Configuration Class
 */
class Config
{
    /**
     * @var array
     */
    private static $confArray;

    /**
     * @param string $name
     * @return mixed
     */
    public static function read($name)
    {
        return self::$confArray[$name];
    }

    /**
     * @param $name
     * @param $value
     */
    public static function write($name, $value)
    {
        self::$confArray[$name] = $value;
    }
}
