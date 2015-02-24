<?php
// PHP OOP Login/Register System: Config Class (Part 6/23)

// Instead of doing this
// $GLOBALS['config']['mysql']['host'];
// we create something like this

class Config {
    public static function get($path = null) {
        if($path) {
            $config = $GLOBALS['config'];
            // explodes string $path(from above) into array assigned to var $path;
            $path = explode('/', $path);
            // foreach iterates over arrays or objects
            foreach($path as $bit) {
                // if $bit exists in $config = $GLOBALS['config'];
                if(isset($config[$bit])) {
                    $config = $config[$bit];
                }
            }
            return $config;
        }

        return false;
    }
}