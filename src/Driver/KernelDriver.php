<?php

namespace Laracasts\Behat\Driver;

use Behat\Mink\Driver\BrowserKitDriver;
use Symfony\Component\HttpKernel\Client;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class KernelDriver extends BrowserKitDriver
{

    /**
     * Create a new KernelDriver.
     *
     * @param HttpKernelInterface $app
     * @param string|null         $baseUrl
     */
    public function __construct(HttpKernelInterface $app, $baseUrl = null)
    {
        parent::__construct(new Client($app), $baseUrl);
    }

    /**
     * Refresh the driver.
     *
     * @param HttpKernelInterface $app
     * @return KernelDriver
     */
    public function reboot($app)
    {
        return $this->__construct($app);
    }

}
