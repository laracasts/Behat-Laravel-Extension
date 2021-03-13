<?php

namespace Laracasts\Behat\ServiceContainer;

use Behat\MinkExtension\ServiceContainer\Driver\DriverFactory;
use Laracasts\Behat\Driver\KernelDriver;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

final class LaravelFactory implements DriverFactory
{
    public const LARAVEL_DRIVER = 'laravel';

    public function getDriverName()
    {
        return self::LARAVEL_DRIVER;
    }

    public function supportsJavascript()
    {
        return false;
    }

    public function configure(ArrayNodeDefinition $builder)
    {
    }

    public function buildDriver(array $config)
    {
        return new Definition(KernelDriver::class, [ new Reference(BehatExtension::LARAVEL_APP), '%mink.base_url%' ]);
    }
}
