<?php

namespace Laracasts\Behat\Context;

use Behat\Behat\Context\Context;
use Laravel\Lumen\Application;

interface ApplicationAwareContext extends Context
{
    /**
     * Set the kernel instance on the context.
     *
     * @param Application $container
     * @return mixed
     */
    public function setApp(Application $container);
}
