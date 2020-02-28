<?php

namespace IsTianXin\Decorator\Tests\Middleware;

class AdditionMiddleware
{
    public function handle($data, $next, $addend = 1, $another_addend = 0)
    {
        return $next($data) + $addend + $another_addend;
    }
}