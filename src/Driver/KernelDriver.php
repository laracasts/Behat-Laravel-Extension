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
        // TODO: No idea why this needs to be here in order to work...
        require __DIR__ . '/../../bootstrap/app.php';

        parent::__construct(new Client($app), $baseUrl);
    }

}
