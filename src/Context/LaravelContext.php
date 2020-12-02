<?php

namespace Laracasts\Behat\Context;

use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\RawMinkContext;
use Features\Traits\Assertable;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\Concerns\InteractsWithAuthentication;
use Illuminate\Foundation\Testing\Concerns\InteractsWithConsole;
use Illuminate\Foundation\Testing\Concerns\InteractsWithContainer;
use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;
use Illuminate\Foundation\Testing\Concerns\InteractsWithExceptionHandling;
use Illuminate\Foundation\Testing\Concerns\InteractsWithRedis;
use Illuminate\Foundation\Testing\Concerns\InteractsWithSession;
use Illuminate\Foundation\Testing\Concerns\MakesHttpRequests;
use Illuminate\Foundation\Testing\Concerns\MocksApplicationServices;
use Laracasts\Behat\Context\DatabaseTransactions;
use PHPUnit\Framework\Assert;
use Tests\CreatesApplication;

abstract class LaravelContext extends RawMinkContext implements Context
{
    use DatabaseTransactions,
        MakesHttpRequests,
        InteractsWithAuthentication,
        InteractsWithConsole,
        InteractsWithContainer,
        InteractsWithDatabase,
        InteractsWithExceptionHandling,
        InteractsWithRedis,
        InteractsWithSession,
        MakesHttpRequests,
        MocksApplicationServices,
        CreatesApplication;

    protected Application $app;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->app = $this->createApplication();
    }

    // todo: map all methods from Asserts and create separate methods
    public function __call($method, $arguments)
    {
        if (method_exists(Assert::class, $method)) {
            call_user_func_array([Assert::class, $method], $arguments);
        }
    }

}
