<?php

namespace Laracasts\Behat\Context;

use Symfony\Component\HttpKernel\HttpKernelInterface;

trait App
{

    /**
     * The Laravel application.
     *
     * @var HttpKernelInterface
     */
    protected $app;

    /**
     * Set the application.
     *
     * @param HttpKernelInterface $app
     */
    public function setApp(HttpKernelInterface $app)
    {
        $this->app = $app;
    }

    /**
     * Get the application.
     *
     * @return mixed
     */
    public function app()
    {
        return $this->app;
    }

}