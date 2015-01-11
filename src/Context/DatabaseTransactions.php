<?php

namespace Laracasts\Behat\Context;

use DB;

trait DatabaseTransactions
{

    /**
     * Begin a database transaction.
     *
     * @BeforeScenario
     */
    public static function beginTransaction()
    {
        DB::beginTransaction();
    }

    /**
     *
     * Roll it back after the scenario.
     *
     * @AfterScenario
     */
    public static function rollback()
    {
        DB::rollback();
    }

}