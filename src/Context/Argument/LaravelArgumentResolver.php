<?php

namespace Laracasts\Behat\Context\Argument;

use ReflectionClass;
use Illuminate\Contracts\Container\Container;
use Behat\Behat\Context\Argument\ArgumentResolver;

class LaravelArgumentResolver implements ArgumentResolver
{
    /** @var Container */
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function resolveArguments(ReflectionClass $classReflection, array $arguments)
    {
        $resolvedArguments = [];

        foreach ($arguments as $key => $argument) {
            $resolvedArguments[$key] = $this->resolveArgument($argument);
        }

        return $resolvedArguments;
    }

    private function resolveArgument($arg)
    {
        if (is_string($arg) === true && substr($arg, 0, 1) === '@') {
            return $this->container->make(substr($arg, 1));
        }

        return $arg;
    }
}
