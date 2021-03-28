<?php

namespace Cevinio\Behat\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\Initializer\ContextInitializer;
use Behat\Behat\EventDispatcher\Event\ScenarioTested;
use Behat\Testwork\EventDispatcher\Event\SuiteTested;
use Cevinio\Behat\ServiceContainer\LaravelFactory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class LaravelAwareInitializer implements EventSubscriberInterface, ContextInitializer
{
    /** @var LaravelFactory */
    private $factory;

    public function __construct(LaravelFactory $factory)
    {
        $this->factory = $factory;
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
        if (false === ($context instanceof LaravelAwareContext)) {
            return;
        }

        $context->setLaravelFactory($this->factory);
    }

    public function rebootLaravel(): void
    {
        $this->factory->reboot();
    }
}
