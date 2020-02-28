<?php

namespace IsTianXin\Apollo;

use Illuminate\Support\Facades\Cache;

class ApolloConfig
{
    /**
     * refresh config to cache
     * @param string $path
     * @return bool
     */
    public static function refresh(string $path): bool
    {
        $path = rtrim($path, DIRECTORY_SEPARATOR);

        if (!file_exists($path)) {
            return false;
        }

        $files = glob($path . DIRECTORY_SEPARATOR . 'apolloConfig.*');
        foreach ($files as $file) {
            $config = include $file;
            if (is_array($config) && isset($config['configurations'])) {
                $namespace = $config['namespaceName'] ?: self::parseNamespaceFromFileName($file);
                Cache::store(config('apollo.cache_store') ?: null)
                    ->forever(self::getCacheKey($namespace), $config['configurations']);
            }
        }

        return true;
    }

    /**
     * get config from cache
     * @param $namespace
     * @return mixed
     */
    public static function connect($namespace)
    {
        return Cache::get(self::getCacheKey($namespace));
    }

    /**
     * @param $file_name
     * @return mixed|string
     */
    public static function parseNamespaceFromFileName($file_name)
    {
        if (preg_match('/apolloConfig.(\w+).php$/', $file_name, $matches)) {
            return $matches[1];
        }
        return '';
    }

    /**
     * @param $namespace
     * @return string
     */
    public static function getCacheKey($namespace): string
    {
        return config('apollo.cache_key_prefix') . $namespace;
    }
}
