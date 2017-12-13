<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-12-13
 * Time: 下午 2:48
 */
class QueryListener
{
    /**
     * Create the listener
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event
     * @param QueryListener
     */
    public function handle(QueryExecuted $event)
    {
        $sql = str_replace("?", "'%s'", $event->sql);

        $log = vsprintf($sql, $event->bindings);

        Log::info($log);

    }


}