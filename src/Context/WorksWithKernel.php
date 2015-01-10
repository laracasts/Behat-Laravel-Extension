<?php

namespace Laracasts\Behat\Context;

use Symfony\Component\HttpKernel\HttpKernelInterface;

trait WorksWithKernel
{

    /**
     * The Laravel application.
     *
     * @var HttpKernelInterface
     */
    protected $kernel;

    /**
     * Set the application.
     *
     * @param HttpKernelInterface $kernel
     */
    public function setKernel(HttpKernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * Get the application.
     *
     * @return mixed
     */
    public function getKernel()
    {
        return $this->kernel;
    }

}