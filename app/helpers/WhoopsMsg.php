<?php
/**
 * essential-master
 * Created by SureCoder
 * FILE NAME: WhoopsMsg.php
 * YEAR: 2022
 */


class WhoopsMsg
{
    public function __construct()
    {

    }

    public function initError(){
        $whoops = new \Whoops\Run;
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
        $whoops->register();
    }
}