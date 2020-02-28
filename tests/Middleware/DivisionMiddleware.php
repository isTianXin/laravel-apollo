<?php

namespace IsTianXin\Decorator\Tests\Middleware;

class DivisionMiddleware
{
    public function handle($data, $next, $dividend = 1)
    {
        return $next($data) / $dividend;
    }
}