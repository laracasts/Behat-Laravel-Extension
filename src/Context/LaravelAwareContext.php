<?php

namespace Laracasts\Behat\Context;

use Behat\Behat\Context\Context;
use Behat\Mink\Mink;
use Behat\Mink\Session;
use Illuminate\Foundation\Application;

interface LaravelAwareContext extends Context
{
    public function setApp(Application $app): void;

    /**
     * Returns registered session by it's name or default one.
     *
     * @see Mink::getSession()
     *
     * @param string $name session name
     *
     * @return Session
     *
     * @throws \InvalidArgumentException If the named session is not registered
     */
    public function getSession($name = null);
}
