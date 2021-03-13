<?php

namespace Laracasts\Behat\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\Initializer\ContextInitializer;
use Behat\Behat\EventDispatcher\Event\ScenarioTested;
use Behat\Testwork\EventDispatcher\Event\SuiteTested;
use Illuminate\Foundation\Application;
use Laracasts\Behat\Driver\KernelDriver;
use Laracasts\Behat\ServiceContainer\LaravelBooter;
use Laracasts\Behat\ServiceContainer\LaravelFactory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LaravelAwareInitializer implements EventSubscriberInterface, ContextInitializer
{
    /** @var Application */
    private $app;

    /** @var Context */
    private $context;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public static function getSubscribedEvents()
    {
        return [
            SuiteTested::BEFORE => [ 'rebootLaravel', 15 ],
            ScenarioTested::BEFORE => [ 'rebootLaravel', 15 ],
        ];
    }

    public function initializeContext(Context $context)
    {
        $this->context = $context;
    }

    public function rebootLaravel(): void
    {
        if (!$this->context instanceof LaravelAwareContext) {
            return;
        }

        $this->app->flush();

        $this->app = LaravelBooter::boot($this->app->basePath(), $this->app->environmentFile());

        /** @var KernelDriver $driver */
        $driver = $this->context->getSession(LaravelFactory::LARAVEL_DRIVER)->getDriver();
        $driver->reboot($this->app);

        $this->context->setApp($this->app);
    }
}
