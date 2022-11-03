<?php
/**
 * essential-master
 * Created by SureCoder
 * FILE NAME: Filehandling
 * YEAR: 2022
 */

class Filehandling
{

    /**
     * constructor
     */
    private function __construct() {}

    /**
     *
     * @return string
     */
    public static function mainRoot(): string
    {
        return ROOT;
    }

    /**
     *
     * @return string
     */
    public static function ds(): string
    {
        return DS;
    }

    /**
     *
     * @param string $path
     * @return string $path
     */
    public static function filePath(string $path): string
    {
        $path = static::mainRoot() . static::ds() . trim($path, '/');
        return str_replace(['/', '\\'], static::ds(), $path);
    }

    /**
     *
     * @var string $path
     * @return boolean
     */
    public static function fileChecker(string $path): bool
    {
        return file_exists(static::filePath($path));
    }

    /**
     *
     * @var string $path
     * @return mixed
     */
    public static function require_file(string $path): mixed
    {
        if (static::fileChecker($path)){
            return require_once static::filePath($path);
        }

        return false;
    }

    /**
     *
     * @var string $path
     * @return mixed
     */
    public static function include_file(string $path): mixed
    {
        if (static::fileChecker($path)){
            return include static::filePath($path);
        }

        return false;
    }

    /**
     *
     * @return array|bool
     *@var string $path
     */
    public static function require_directory(string $path)
    {
        $fileList =  array_diff(scandir(static::filePath($path)), ['.', '..']);
        foreach ($fileList as $FL){
            $file_path = $path . static::ds() . $FL;
            if (static::require_file($file_path)){
                static::require_file(($file_path));
            }
        }

        return false;
    }
}