<?php
namespace Laracasts\Behat\Context\Argument;

use \ReflectionClass;
use Illuminate\Contracts\Container\Container;
use Behat\Behat\Context\Argument\ArgumentResolver;

class LaravelArgumentResolver implements ArgumentResolver
{
    /** @var Container Laravel application instance */
    private $app;

    /**
     * @param Container $app
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    /**
     * Resolves context constructor arguments.
     *
     * @param ReflectionClass $classReflection
     * @param mixed[]         $arguments
     *
     * @return mixed[]
     */
    public function resolveArguments(ReflectionClass $classReflection, array $arguments)
    {
        $resolvedArguments = [];

        foreach ($arguments as $key => $argument) {
            $resolvedArguments[$key] = $this->resolveArgument($argument);
        }

        return $resolvedArguments;
    }

    /**
     * Resolve argument
     *
     * @param  string $arg
     * @return object
     */
    private function resolveArgument($arg)
    {
        if (is_string($arg) === true && substr($arg, 0, 1) === '@') {
            return $this->app->make(substr($arg, 1));
        }

        return $arg;
    }
}
