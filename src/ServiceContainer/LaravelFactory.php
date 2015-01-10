<?php

namespace Laracasts\Behat\ServiceContainer;

use Behat\MinkExtension\ServiceContainer\Driver\DriverFactory;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class LaravelFactory implements DriverFactory
{

    /**
     * {@inheritdoc}
     */
    public function getDriverName()
    {
        return 'laravel';
    }

    /**
     * {@inheritdoc}
     */
    public function supportsJavascript()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function configure(ArrayNodeDefinition $builder)
    {
        // TODO: What should go here?
    }

    /**
     * {@inheritdoc}
     */
    public function buildDriver(array $config)
    {
        if ( ! class_exists('Behat\Mink\Driver\BrowserKitDriver')) {
            throw new RuntimeException(
                'Install MinkBrowserKitDriver in order to use the laravel driver.'
            );
        }

        return new Definition('Laracasts\Behat\Driver\KernelDriver', [
            new Reference('laravel.app'),
            '%mink.base_url%'
        ]);
    }

}

