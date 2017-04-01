<?php

namespace Laracasts\Behat\Context;

use Artisan;

trait Seeder
{

    /**
     * Migrate the database before each scenario.
     *
     * @beforeScenario
     */
    public function seed()
    {
        Artisan::call('db:seed');
    }

}
