<?php

namespace Cevinio\Behat\ServiceContainer;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Cevinio\Behat\Driver\LaravelDriver;

final class LaravelFactory
{
    /** @var string */
    private $bootstrapPath;

    /** @var Application|null */
    private $app;

    /** @var LaravelDriver[] */
    private $drivers;

    public function __construct(string $bootstrapPath)
    {
        $this->bootstrapPath = $bootstrapPath;
        $this->app = null;
        $this->drivers = [];
    }

    public function get(): Application
    {
        if (null === $this->app) {
            $this->reboot();
        }

        return $this->app;
    }

    public function reboot(): void
    {
        if (null !== $this->app) {
            $this->app->flush();
            $this->app = null;
        }

        /** @var Application $app */
        $this->app = require $this->bootstrapPath;

        $this->app->make(Kernel::class)->bootstrap();
        $this->app->make(Request::class)->capture();

        foreach ($this->drivers as $driver) {
            $driver->setApplication($this->app);
        }
    }

    public function register(LaravelDriver $driver): void
    {
        $this->drivers[] = $driver;
    }
}
