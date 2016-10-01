<?php

namespace Laracasts\Behat\Context;

use Artisan;

trait MigrateRefresh
{
    /**
     * Migrate the database before each scenario.
     *
     * @beforeScenario
     */
    public function migrate()
    {
        Artisan::call('migrate:refresh');
    }
}
