<?php

namespace Laracasts\Behat\ServiceContainer;

use Behat\MinkExtension\ServiceContainer\MinkExtension;
use Behat\Testwork\ServiceContainer\Extension;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Illuminate\Foundation\Application;
use Laracasts\Behat\Context\LaravelAwareInitializer;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Behat\Behat\Context\ServiceContainer\ContextExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Laracasts\Behat\Context\Argument\LaravelArgumentResolver;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Behat\Testwork\EventDispatcher\ServiceContainer\EventDispatcherExtension;

final class BehatExtension implements Extension
{
    public const LARAVEL_APP = 'laravel.app';

    public function getConfigKey()
    {
        return 'laravel';
    }

    public function initialize(ExtensionManager $extensionManager)
    {
        /** @var MinkExtension $minkExtension */
        $minkExtension = $extensionManager->getExtension('mink');

        if (null !== $minkExtension) {
            $minkExtension->registerDriverFactory(new LaravelFactory());
        }
    }

    public function process(ContainerBuilder $container)
    {
    }

    public function configure(ArrayNodeDefinition $builder)
    {
        $builder->children()->scalarNode('env_path');
    }

    public function load(ContainerBuilder $container, array $config)
    {
        $app = $this->loadLaravel($container, $config);

        $this->loadInitializer($container, $app);
        $this->loadLaravelArgumentResolver($container);
    }

    private function loadLaravel(ContainerBuilder $container, array $config): Application
    {
        $app = LaravelBooter::boot($container->getParameter('paths.base'), $config['env_path'] ?? null);

        $container->set(self::LARAVEL_APP, $app);

        return $app;
    }

    private function loadInitializer(ContainerBuilder $container, Application $app): void
    {
        $definition = new Definition(LaravelAwareInitializer::class, [ $app ]);

        $definition->addTag(EventDispatcherExtension::SUBSCRIBER_TAG, [ 'priority' => 0 ]);
        $definition->addTag(ContextExtension::INITIALIZER_TAG, [ 'priority' => 0 ]);

        $container->setDefinition('laravel.initializer', $definition);
    }

    private function loadLaravelArgumentResolver(ContainerBuilder $container): void
    {
        $definition = new Definition(LaravelArgumentResolver::class, [ new Reference(self::LARAVEL_APP) ]);

        $definition->addTag(ContextExtension::ARGUMENT_RESOLVER_TAG, [ 'priority' => 0 ]);

        $container->setDefinition('laravel.context.argument.service_resolver', $definition);
    }
}
