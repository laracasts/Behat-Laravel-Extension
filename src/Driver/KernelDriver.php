<?php

namespace Laracasts\Behat\Driver;

use Behat\Mink\Driver\BrowserKitDriver;
use Illuminate\Foundation\Application;
use Symfony\Component\HttpKernel\HttpKernelBrowser;

final class KernelDriver extends BrowserKitDriver
{
    /** @var string|null */
    private $baseUrl;

    public function __construct(Application $app, ?string $baseUrl = null)
    {
        parent::__construct(new HttpKernelBrowser($app), $baseUrl);

        $this->baseUrl = $baseUrl;
    }

    public function reboot(Application $app): void
    {
        $this->__construct($app, $this->baseUrl);
    }
}
