<?php

namespace Cevinio\Behat\ServiceContainer;

use Behat\MinkExtension\ServiceContainer\MinkExtension;
use Behat\Testwork\ServiceContainer\Extension;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Cevinio\Behat\Context\LaravelAwareInitializer;
use Cevinio\Behat\Driver\LaravelDriverFactory;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Behat\Behat\Context\ServiceContainer\ContextExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Cevinio\Behat\Context\Argument\LaravelArgumentResolver;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Behat\Testwork\EventDispatcher\ServiceContainer\EventDispatcherExtension;

final class BehatExtension implements Extension
{
    public const CONFIG_KEY = 'laravel';
    public const LARAVEL_FACTORY = 'laravel.factory';
    public const LARAVEL_INITIALIZER = 'laravel.initializer';
    public const LARAVEL_RESOLVER = 'laravel.resolver';

    public function getConfigKey()
    {
        return self::CONFIG_KEY;
    }

    public function initialize(ExtensionManager $extensionManager)
    {
        /** @var MinkExtension $minkExtension */
        $minkExtension = $extensionManager->getExtension('mink');

        if (null !== $minkExtension) {
            $minkExtension->registerDriverFactory(new LaravelDriverFactory());
        }
    }

    public function process(ContainerBuilder $container)
    {
    }

    public function configure(ArrayNodeDefinition $builder)
    {
    }

    public function load(ContainerBuilder $container, array $config)
    {
        $bootstrapPath = $container->getParameter('paths.base') . '/bootstrap/app.php';

        $definition = new Definition(LaravelFactory::class, [ $bootstrapPath ]);
        $container->setDefinition(self::LARAVEL_FACTORY, $definition);

        $definition = new Definition(LaravelAwareInitializer::class, [ new Reference(self::LARAVEL_FACTORY) ]);
        $definition->addTag(EventDispatcherExtension::SUBSCRIBER_TAG, [ 'priority' => 0 ]);
        $definition->addTag(ContextExtension::INITIALIZER_TAG, [ 'priority' => 0 ]);
        $container->setDefinition(self::LARAVEL_INITIALIZER, $definition);

        $definition = new Definition(LaravelArgumentResolver::class, [ new Reference(self::LARAVEL_FACTORY) ]);
        $definition->addTag(ContextExtension::ARGUMENT_RESOLVER_TAG, [ 'priority' => 0 ]);
        $container->setDefinition(self::LARAVEL_RESOLVER, $definition);
    }
}
