<?php

class ncYaml
{
    public static function load($ymlFilePath) {
        $cacher = self::getCacher();
        $realPath = realpath($ymlFilePath);

        $cacheKey = self::getCacheKey($realPath);
        $data = unserialize($cacher->get($cacheKey));

        if (self::shouldYamlBeParsed($realPath, $data)) {
            $data = self::getData($ymlFilePath);
            $cacher->set($cacheKey, serialize($data));
        }

        return $data['content'];
    }

    private static function getCacher() {
        $forceNullCache = self::isConfigExtensionLoaded();
        return ncYamlFactory::getCacher($forceNullCache);
    }

    private static function isConfigExtensionLoaded() {
        return extension_loaded('configprovider');
    }

    private static function getCacheKey($path) {
        return 'nc'.md5($path);
    }

    private static function shouldYamlBeParsed($path, $data) {
        return (!isset($data['content']) || filemtime($path) > $data['cache_time']);
    }

    private static function getData($path) {
        $config = null;

        if (self::isConfigExtensionLoaded()) {
            $provider = new ConfigProvider\ConfigProvider();
            $config = $provider->getConfig($path);
        }

        if (is_null($config)) {
            $config = (file_exists($path)) ? (array) sfYaml::load($path) : array();
        }

        return array('content' => $config, 'cache_time' => time());
    }
}

