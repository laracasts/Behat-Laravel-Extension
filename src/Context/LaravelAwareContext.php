<?php

namespace Cevinio\Behat\Context;

use Behat\Behat\Context\Context;
use Behat\Mink\Mink;
use Behat\Mink\Session;
use Illuminate\Foundation\Application;
use Cevinio\Behat\ServiceContainer\LaravelFactory;

interface LaravelAwareContext extends Context
{
    public function setLaravelFactory(LaravelFactory $factory): void;
}
