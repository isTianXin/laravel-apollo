<?php

namespace IsTianXin\Decorator\Tests\Middleware;

class MultiplicationMiddleware
{
    public function handle($data, $next, $factor = 1)
    {
        return $next($data) * $factor;
    }
}