<?php
use Illuminate\Container\Container;
function di()
{
    static $di = null;
    $di = $di ? $di: new Container();
    return $di;
}



