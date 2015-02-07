<?php

namespace Laracasts\Behat\Context;

use Behat\Behat\Context\Context;
use Symfony\Component\HttpKernel\HttpKernelInterface;

interface KernelAwareContext extends Context
{

    /**
     * Set the kernel instance on the context.
     *
     * @param HttpKernelInterface $kernel
     * @return mixed
     */
    public function setApp(HttpKernelInterface $kernel);

    /**
     * Returns the specified session or active session
     *
     * @param string|null $name name of the session
     * @return mixed
     */
    public function getSession($name = null);

}