<?php

namespace IsTianXin\Apollo;

use Illuminate\Support\Arr;
use Org\Multilinguals\Apollo\Client\ApolloClient;

class ApolloService
{
    /**
     * @var ApolloClient
     */
    protected $client;

    protected function prepare()
    {
        // build client
        $client = new ApolloClient(
            config('apollo.config_server'),
            config('apollo.app_id'),
            Helper::arrayWrap(config('apollo.namespaces'))
        );
        $client->save_dir = config('apollo.save_dir');

        //set additional config
        if (config('apollo.cluster')) {
            $client->setCluster(config('apollo.cluster'));
        }
        if (config('apollo.client_ip')) {
            $client->setClientIp(config('apollo.client_ip'));
        }
        if (config('apollo.pull_timeout') || is_numeric(config('apollo.pull_timeout'))) {
            $client->setPullTimeout(config('apollo.pull_timeout'));
        }
        if (config('apollo.interval_timeout') || is_numeric(config('apollo.interval_timeout'))) {
            $client->setIntervalTimeout(config('apollo.interval_timeout'));
        }

        $this->client = $client;
    }

    /**
     * @return ApolloClient
     */
    public function getClient(): ApolloClient
    {
        if ($this->client === null) {
            $this->prepare();
        }
        return $this->client;
    }

    /**
     * @param $namespace
     * @param string $key
     * @param $default
     * @return mixed
     */
    public function getConfig($namespace = 'application', $key = null, $default = null)
    {
        if ($key === null) {
            return ApolloConfig::get($namespace);
        }

        return Arr::get(Helper::arrayWrap(ApolloConfig::get($namespace)), $key, $default);
    }

    /**
     * @return bool
     */
    public function refreshConfig(): bool
    {
        return ApolloConfig::refresh($this->client->save_dir);
    }

    /**
     * change listener
     * @return \Closure
     */
    public function listener(): callable
    {
        return function () {
            return ApolloConfig::refresh($this->client->save_dir);
        };
    }
}
