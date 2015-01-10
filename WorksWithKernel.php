<?php

use Symfony\Component\HttpKernel\HttpKernelInterface;

trait WorksWithKernel {

    /**
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