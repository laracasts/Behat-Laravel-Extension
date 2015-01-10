<?php

namespace Laracasts\Behat\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\Initializer\ContextInitializer;
use Behat\Behat\EventDispatcher\Event\ScenarioTested;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class KernelAwareInitializer implements EventSubscriberInterface, ContextInitializer
{

    /**
     * @var object
     */
    private $kernel;

    /**
     * Construct the initializer.
     *
     * @param HttpKernelInterface $kernel
     */
    public function __construct(HttpKernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            ScenarioTested::AFTER => ['rebootKernel', -15]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function initializeContext(Context $context)
    {
        if ( ! $context instanceof KernelAwareContext) {
            return;
        }

        $context->setKernel($this->kernel);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsContext(Context $context)
    {
        return true;
    }

    /**
     *
     */
    public function rebootKernel()
    {
        // TODO: Find better place for this.

        $app = require $this->kernel->basePath() . '/bootstrap/app.php';

        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    }

}