<?php

namespace Laracasts\Behat\ServiceContainer;

use Behat\Behat\Context\ServiceContainer\ContextExtension;
use Behat\Testwork\EventDispatcher\ServiceContainer\EventDispatcherExtension;
use Behat\Testwork\ServiceContainer\Extension;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Laracasts\Behat\ServiceContainer\LaravelFactory;

class LaravelExtension implements Extension
{

    /**
     * {@inheritdoc}
     */
    public function getConfigKey()
    {
        return 'laravel';
    }

    /**
     * {@inheritdoc}
     */
    public function initialize(ExtensionManager $extensionManager)
    {
        if (null !== $minkExtension = $extensionManager->getExtension('mink')) {
            $minkExtension->registerDriverFactory(new LaravelFactory);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        //
    }

    /**
     * {@inheritdoc}
     */
    public function configure(ArrayNodeDefinition $builder)
    {
        $builder
            ->children()
                ->scalarNode('bootstrap_path')
                    ->defaultValue('foobar')
                ->end()
                ->scalarNode('env_path')
                    ->defaultValue('.env.behat');
    }

    /**
     * {@inheritdoc}
     */
    public function load(ContainerBuilder $container, array $config)
    {
        $this->loadApplication($container, $config);
        $this->loadInitializer($container);
    }

    /**
     * Boot up Laravel.
     *
     * @param ContainerBuilder $container
     * @param array            $config
     */
    private function loadApplication(ContainerBuilder $container, array $config)
    {
        $app = require __DIR__ . '/../../../../../bootstrap/app.php';

        $app->loadEnvironmentFrom($config['env_path']);

        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

        $container->register('laravel.app', $app);
    }

    /**
     * Load the initializer.
     *
     * @param ContainerBuilder $container
     */
    private function loadInitializer(ContainerBuilder $container)
    {
        $definition = new Definition('Laracasts\Behat\Context\KernelAwareInitializer', [
            new Reference('laravel.app')
        ]);

        $definition->addTag(EventDispatcherExtension::SUBSCRIBER_TAG, ['priority' => 0]);
        $definition->addTag(ContextExtension::INITIALIZER_TAG, array('priority' => 0));

        $container->setDefinition('laravel.initializer', $definition);
    }

}