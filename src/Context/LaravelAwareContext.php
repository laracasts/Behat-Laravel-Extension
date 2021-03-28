<?php

namespace Cevinio\Behat\Context;

use Behat\Behat\Context\Context;
use Cevinio\Behat\ServiceContainer\LaravelFactory;

interface LaravelAwareContext extends Context
{
    public function setLaravelFactory(LaravelFactory $factory): void;
}
