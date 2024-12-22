<?php
########################################
#
# SCRIPT BY TPNET CYBER
# BY LYXSEC
# COPYRIGHT TPNETCY
# SINCE 2023
# FOUND BUG JUST COMMENT IN MY CHANNEL
#
########################################
namespace Helpers;

class EnvLoader {
    public static function load($file) {
        if (!file_exists($file)) {
            throw new \Exception("File .env not found, please configure with ./config; $file");
        }

        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0 || strpos($line, '=') === false) {
                continue;
            }

            list($key, $value) = array_map('trim', explode('=', $line, 2));
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value; // save to variable env
        }
    }

    public static function get($key, $default = null) {
        return $_ENV[$key] ?? $default;
    }
}