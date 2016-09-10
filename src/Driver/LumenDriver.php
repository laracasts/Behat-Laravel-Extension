<?php

namespace Laracasts\Behat\Driver;

use Behat\Mink\Driver\BrowserKitDriver;
use Laravel\Lumen\Application;

class LumenDriver extends BrowserKitDriver
{
    /**
     * Create a new Driver.
     *
     * @param Application $app
     * @param string|null $baseUrl
     */
    public function __construct(Application $app, $baseUrl = null)
    {
        parent::__construct(new LumenClient($app), $baseUrl);
    }

    /**
     * Refresh the driver.
     *
     * @param Application $app
     * @return LumenDriver
     */
    public function reboot($app)
    {
        return self::__construct($app);
    }
}
