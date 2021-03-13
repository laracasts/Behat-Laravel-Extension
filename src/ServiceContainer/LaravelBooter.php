<?php

namespace Laracasts\Behat\ServiceContainer;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

final class LaravelBooter
{
    public static function boot(string $basePath, ?string $environmentFile = null): Application
    {
        $bootstrapPath = $basePath . '/bootstrap/app.php';

        /** @var Application $app */
        $app = require $bootstrapPath;

        if (null !== $environmentFile) {
            $app->loadEnvironmentFrom($environmentFile);
        }

        $app->make(Kernel::class)->bootstrap();
        $app->make(Request::class)->capture();

        return $app;
    }
}
