<?php

namespace Laracasts\Behat\Context;

use Artisan;

trait Migrator
{

    /**
     * Migrate the database before each scenario.
     *
     * @beforeScenario
     */
    public function migrate()
    {
        Artisan::call('migrate');
    }

}
