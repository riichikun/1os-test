<?php

class Log {
    protected static $dateFormat = 'H:i:s d.m.Y';
    public static function log(string $type, int|string|array|object $text, string $file) {
        file_put_contents($file, date(static::$dateFormat) . ' ' . $type . ' ' . print_r($text, true) . PHP_EOL, FILE_APPEND);
    }
}