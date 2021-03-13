<?php

namespace Laracasts\Behat\Context;

use Illuminate\Foundation\Application;

trait LaravelAware
{
    /** @var Application */
    protected $app;

    /**
     * @see LaravelAwareContext::setApp()
     */
    public function setApp(Application $app): void
    {
        $this->app = $app;
    }

    public function app(): Application
    {
        return $this->app;
    }
}
