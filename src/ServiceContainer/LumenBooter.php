<?php

namespace Laracasts\Behat\ServiceContainer;

use RuntimeException;

class LumenBooter
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
     * Create a new Lumen booter instance.
     *
     * @param string $basePath
     * @param string $environmentFile
     */
    public function __construct($basePath, $environmentFile = '.env')
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
     * Boot the app.
     *
     * @return \Illuminate\Container\Container
     */
    public function boot()
    {
        $bootstrapPath = $this->basePath() . '/bootstrap/app.php';
        $this->assertBootstrapFileExists($bootstrapPath);

        global $dotEnvFile;
        $dotEnvFile = $this->environmentFile;
        $app = require $bootstrapPath;

        return $app;
    }

    /**
     * Ensure that the provided Lumen bootstrap path exists.
     *
     * @param string $bootstrapPath
     * @throws RuntimeException
     */
    private function assertBootstrapFileExists($bootstrapPath)
    {
        if (!file_exists($bootstrapPath)) {
            throw new RuntimeException('Could not locate the path to the Laravel bootstrap file.');
        }
    }
}
