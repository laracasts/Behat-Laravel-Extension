<?php

namespace Cevinio\Behat\Driver;

use Behat\MinkExtension\ServiceContainer\Driver\DriverFactory;
use Cevinio\Behat\ServiceContainer\BehatExtension;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

final class LaravelDriverFactory implements DriverFactory
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
        return new Definition(
            LaravelDriver::class,
            [ new Reference(BehatExtension::LARAVEL_FACTORY), '%mink.base_url%' ]
        );
    }
}
