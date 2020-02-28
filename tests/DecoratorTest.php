<?php

namespace IsTianXin\Decorator\Tests;

use Closure;
use ErrorException;
use IsTianXin\Decorator\Decorator;
use IsTianXin\Decorator\Tests\Dummy\Math;
use IsTianXin\Decorator\Tests\Middleware\AdditionMiddleware;
use IsTianXin\Decorator\Tests\Middleware\DivisionMiddleware;
use IsTianXin\Decorator\Tests\Middleware\MultiplicationMiddleware;
use IsTianXin\Decorator\Tests\Middleware\SubtractionMiddleware;
use Org\Multilinguals\Apollo\Client\ApolloClient;

class DecoratorTest extends TestCase
{
    public function testClosureCallback()
    {
        $client = new ApolloClient();
        $client->setPullTimeout();
        //closure
        $func = static function ($num) {
            return $num;
        };
        $number = 1;
        $decorator = new Decorator();

        // object
        $middleware = new AdditionMiddleware();
        $result = $decorator->setMiddleware($middleware)
            ->setCallback($func)
            ->setParameters($number)
            ->decorate();
        $this->assertEquals($number + 1, $result);
        $this->assertEquals(Closure::class, $decorator->getNormalizedCallbackName());
    }

    public function testClassCallback()
    {
        //anonymous class
        $class = new class {
            public function add($a, $b)
            {
                return $a + $b;
            }
        };
        $a = 1;
        $b = 2;
        $minuend = 3;
        $decorator = new Decorator();

        // classname with parameter
        $middleware = SubtractionMiddleware::class . ':' . $minuend;
        $result = $decorator->setCallback([$class, 'add'])
            ->setMiddleware($middleware)
            ->setParameters([$a, $b])
            ->decorate();
        $this->assertEquals($a + $b - $minuend, $result);
        $this->assertEquals(get_class($class) . '@add', $decorator->getNormalizedCallbackName());

        //normal class
        $math = new Math();
        $factor = 2;
        $middleware = MultiplicationMiddleware::class . ':' . $factor;
        $result = $decorator->setCallback([$math, 'divide'])
            ->setMiddleware($middleware)
            ->decorate();
        $this->assertEquals($a / $b * $factor, $result);
        $this->assertEquals(get_class($math) . '@divide', $decorator->getNormalizedCallbackName());

        // static method
        $result = $decorator->setCallback([$math, 'multiply'])->decorate();
        $this->assertEquals($a * $b * $factor, $result);
        $this->assertEquals(get_class($math) . '@multiply', $decorator->getNormalizedCallbackName());
    }

    public function testStringCallback()
    {
        $decorator = new Decorator();
        $minuend = 2;
        $middleware = new SubtractionMiddleware($minuend);
        $a = 1;
        $b = 2;
        // classname
        $result = $decorator->setCallback([Math::class, 'subtract'])
            ->setMiddleware($middleware)
            ->setParameters(['a' => $a, 'b' => $b])
            ->decorate();

        $this->assertEquals($a - $b - $minuend, $result);
        $this->assertEquals(Math::class . '@subtract', $decorator->getNormalizedCallbackName());

        // classname with static method
        $callback = Math::class . '@subtract';
        $result = $decorator->setCallback($callback)
            //Note that parameter name is specified
            ->setParameters(['b' => $b, 'a' => $a])
            ->decorate();
        $this->assertEquals($a - $b - $minuend, $result);
        $this->assertEquals($callback, $decorator->getNormalizedCallbackName());

        // static method
        $callback = Math::class . '::subtract';
        $result = $decorator->setCallback($callback)->decorate();
        $this->assertEquals($a - $b - $minuend, $result);
        $this->assertEquals($callback, $decorator->getNormalizedCallbackName());

        // classname with non static method
        $callback = Math::class . '@add';
        $result = $decorator->setCallback($callback)
            ->decorate();

        $this->assertEquals($a + $b - $minuend, $result);
        $this->assertEquals($callback, $decorator->getNormalizedCallbackName());
    }

    public function testCallbackWrong()
    {
        $decorator = new Decorator();

        $decorator->setCallback([Math::class])
            ->setParameters([1, 2])
            ->setMiddleware(AdditionMiddleware::class);
        $this->assertEquals('unknown', $decorator->getNormalizedCallbackName());
        $this->expectException(ErrorException::class);
        $decorator->decorate();
    }

    public function testMultipleMiddleware()
    {
        $decorator = new Decorator();
        $a = 20;
        $b = 2;
        $addend = 1;
        $another_addend = 2;
        $minuend = 1;
        $factor = 10;
        $dividend = 2;

        //middleware with two parameters
        $addition_middleware = AdditionMiddleware::class . ':' . $addend . ',' . $another_addend;
        $subtraction_middleware = new SubtractionMiddleware();
        // closure function
        $multiple_middleware = function ($data, $next) use ($factor) {
            return $next($data) * $factor;
        };
        $division_middleware = DivisionMiddleware::class . ':' . $dividend;

        $result = $decorator->setCallback([Math::class, 'multiply'])
            ->setMiddleware([$addition_middleware, $subtraction_middleware])
            ->appendMiddleware([$multiple_middleware, $division_middleware])
            ->setParameters([$a, $b])
            ->decorate();

        $this->assertEquals(($a * $b) / $dividend * $factor - $minuend + $addend + $another_addend, $result);
        $this->assertEquals(Math::class . '@multiply', $decorator->getNormalizedCallbackName());
    }
}
