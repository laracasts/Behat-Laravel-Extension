<?php

namespace Cevinio\Behat\Context;

use Illuminate\Foundation\Application;
use Cevinio\Behat\ServiceContainer\LaravelFactory;

trait LaravelAware
{
    /** @var LaravelFactory */
    private $factory;

    /**
     * @see LaravelAwareContext::setLaravelFactory()
     * @internal
     */
    public function setLaravelFactory(LaravelFactory $factory): void
    {
        $this->factory = $factory;
    }

    public function app(): Application
    {
        return $this->factory->get();
    }
}
