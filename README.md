# Laravel Apollo

Laravel Apollo Client

## Installation 

Via composer
> composer require istianxin/laravel-apollo

Edit .env
```
APOLLO_CONFIG_SERVER=your_apollo_service_url
APOLLO_APP_ID=your_app_id
APOLLO_CLUSTER=default
APOLLO_CLIENT_IP=127.0.0.1
APOLLO_NAMESPACES=application,test
APOLLO_PULL_TIMEOUT=10
APOLLO_INTERVAL_TIMEOUT=72
APOLLO_CACHE_STORE=redis
APOLLO_CACHE_KEY_PREFIX=apollo_config
```

### Laravel 5.x

Add provider
> IsTianXin\Apollo\ApolloServiceProvider::class

Publish 
>  php artisan vendor:publish --provider="IsTianXin\Apollo\ApolloServiceProvider"

### Laravel 6.x

Publish
>  php artisan vendor:publish --tag=apollo

## QuickStart

Start as a daemon
> php artisan apollo:start

Get config
> app()->make(ApolloService::class)->getConfig($namespace,$key,$default);

## Cache

Laravel apollo use laravel cache driver to store config. 
You can edit ```APOLLO_CACHE_STORE``` to change cache driver from redis(default) to another.

