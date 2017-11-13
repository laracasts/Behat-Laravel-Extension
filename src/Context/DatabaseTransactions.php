<?php

namespace Laracasts\Behat\Context;

use DB, Cache;

trait DatabaseTransactions
{

    /**
     * The database connections that should have transactions.
     *
     * @return array
     */
    protected function connectionsToTransact()
    {
        return property_exists($this, 'connectionsToTransact')
            ? $this->connectionsToTransact : [null];
    }
    
    /**
     * Begin a database transaction.
     *
     * @BeforeScenario
     */
    public function beginTransaction()
    {
        foreach ($this->connectionsToTransact() as $name) {
            DB::connection($name)->beginTransaction();
        }
    }

    /**
     *
     * Roll it back after the scenario.
     *
     * @AfterScenario
     */
    public function rollback()
    {
        foreach ($this->connectionsToTransact() as $name) {
            DB::connection($name)->rollBack();
        }
        Cache::flush();
    }

}
