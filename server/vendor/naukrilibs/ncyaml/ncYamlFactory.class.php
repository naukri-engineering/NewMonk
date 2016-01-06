<?php

/** class ncYamlFactory
 *
 */
class ncYamlFactory
{
    public static function getCacher($forceNullCache = false) {
        if ($forceNullCache) {
            $cacher = new ncNullCache();
        }
        elseif (self::checkForXCache()) {
            $cacher = new ncXCache();
        }
        elseif (self::checkForApcCache()) {
            $cacher = new ncApcCache();
        }
        elseif (self::checkForFileCache() ) {
            $cacheDir = (defined('YAML_CACHE_DIR')) ? YAML_CACHE_DIR : sfConfig::get('sf_cache_dir').'/yaml';
            $cacher = new ncFileCache($cacheDir);
        }
        else {
            $cacher = new ncNullCache();
        }

        return $cacher;
    }

    private static function checkForXCache() {
        return (function_exists('xcache_set') && ini_get('xcache.var_size') > 0);
    }

    private static function checkForApcCache() {
        return function_exists('apc_store') && function_exists('apc_exists');
    }

    private static function checkForFileCache() {
        try {
            return ( (defined('YAML_CACHE_DIR')) || (class_exists('sfConfig') && sfConfig::get('sf_cache_dir') != '') );
        }
        catch(Exception $e) {
            //autoloading may throw an exception, if class does not exist.
            return false;
        }
    }
}

