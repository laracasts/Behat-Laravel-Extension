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
     * The application's bootstrap file.
     *
     * @var string
     */
    private $bootstrapFile;

    /**
     * Create a new Laravel booter instance.
     *
     * @param        $basePath
     * @param string $environmentFile
     * @param string $bootstrapFile
     */
    public function __construct($basePath, $environmentFile = '.env.behat', $bootstrapFile = 'bootstrap/app.php')
    {
        $this->basePath = $basePath;
        $this->environmentFile = $environmentFile;
        $this->bootstrapFile = $bootstrapFile;
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
     * Get the applications bootstrap file.
     *
     * @return string
     */
    public function bootstrapFile()
    {
        return ltrim($this->bootstrapFile, '/');
    }

    /**
     * Boot the app.
     *
     * @return mixed
     */
    public function boot()
    {
        $bootstrapFile = $this->bootstrapFile();
        $bootstrapPath = $this->basePath() . "/$bootstrapFile";

        $this->assertBootstrapFileExists($bootstrapPath);

        $app = require $bootstrapPath;

        $app->loadEnvironmentFrom($this->environmentFile());

        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

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
