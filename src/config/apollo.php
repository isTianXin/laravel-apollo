<?php

return [
    'config_server' => env('APOLLO_CONFIG_SERVER', 'forge'),
    'app_id' => env('APOLLO_APP_ID', 'forge'),
    'cluster' => env('APOLLO_CLUSTER'),
    'client_ip' => env('APOLLO_CLIENT_IP'),
    'namespaces' => explode(',', env('APOLLO_NAMESPACES', 'application')),
    'pull_timeout' => env('APOLLO_PULL_TIMEOUT'),
    'interval_timeout' => env('APOLLO_INTERVAL_TIMEOUT'),
    'save_dir' => realpath(storage_path('apollo')),
    'cache_store' => env('APOLLO_CACHE_STORE'),
    'cache_key_prefix' => env('APOLLO_CACHE_KEY_PREFIX', 'apollo_config'),
];
