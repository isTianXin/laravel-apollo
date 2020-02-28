<?php

namespace IsTianXin\Decorator\Tests\Middleware;

class SubtractionMiddleware
{
    private $minuend;

    public function __construct($minuend = 1)
    {
        $this->minuend = $minuend;
    }

    public function handle($data, $next, $minuend = null)
    {
        return $next($data) - ($minuend ?? $this->minuend);
    }
}