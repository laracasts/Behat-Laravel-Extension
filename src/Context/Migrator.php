<?php

namespace Laracasts\Behat\Context;

use Artisan;

trait Migrator
{

    /**
     * Migrat the databse before each scenario.
     *
     * @beforeScenario
     */
    public function migrate()
    {
        Artisan::call('migrate');
    }

}
