<?php

namespace Laracasts\Behat\ServiceContainer;

use RuntimeException;

class LaravelBooter
{

    /**
     * The base path for the application.
     *
     * @var string
     */
    private $basePath;

    /**
     * The application's environment file.
     *
     * @var string
     */
    private $environmentFile;

    /**
     * Create a new Laravel booter instance.
     *
     * @param        $basePath
     * @param string $environmentFile
     */
    public function __construct($basePath, $environmentFile = '.env.behat')
    {
        $this->basePath = $basePath;
        $this->environmentFile = $environmentFile;
    }

    /**
     * Get the application's base path.
     *
     * @return mixed
     */
    public function basePath()
    {
        return $this->basePath;
    }

    /**
     * Get the application's environment file.
     *
     * @return string
     */
    public function environmentFile()
    {
        return $this->environmentFile;
    }


    /**
     * Boot the app.
     *
     * @return mixed
     */
    public function boot()
    {
        $bootstrapPath = $this->basePath() . '/bootstrap/app.php';

        $this->assertBootstrapFileExists($bootstrapPath);

        $app = require $bootstrapPath;

        $app->loadEnvironmentFrom($this->environmentFile());

        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

        $app->make('Illuminate\Http\Request')->capture();

        return $app;
    }

    /**
     * Ensure that the provided Laravel bootstrap path exists.
     *
     * @param string $bootstrapPath
     * @throws RuntimeException
     */
    private function assertBootstrapFileExists($bootstrapPath)
    {
        if ( ! file_exists($bootstrapPath)) {
            throw new RuntimeException('Could not locate the path to the Laravel bootstrap file.');
        }
    }

}