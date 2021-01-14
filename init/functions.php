<?php

use Illuminate\Container\Container;

if(!function_exists('di')){

    function di()
    {
        static $di = null;
        $di = $di ? $di: new Container();
        return $di;
    }
}