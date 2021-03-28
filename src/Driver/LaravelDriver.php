<?php

namespace Cevinio\Behat\Driver;

use Behat\Mink\Driver\BrowserKitDriver;
use Illuminate\Foundation\Application;
use Cevinio\Behat\ServiceContainer\LaravelFactory;
use Symfony\Component\HttpKernel\HttpKernelBrowser;

final class LaravelDriver extends BrowserKitDriver
{
    private $baseUrl;

    public function __construct(LaravelFactory $factory, $baseUrl = null)
    {
        $this->baseUrl = $baseUrl;

        $this->setApplication($factory->get());

        $factory->register($this);
    }

    /** @internal */
    public function setApplication(Application $app): void
    {
        parent::__construct(new HttpKernelBrowser($app), $this->baseUrl);
    }
}
