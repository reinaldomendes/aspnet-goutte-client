<?php
/**
 * Boostrap file
 * * 
 */
define('APP_DIR' , dirname(__DIR__));
// require APP_DIR . '/bootstrap.php';

define('TEST_DIR' , APP_DIR . '/tests');
define('FIXTURES_DIR' , TEST_DIR . '/fixtures');



use Illuminate\Container\Container;
function di()
{
    static $di = null;
    $di = $di ? $di: new Container();
    return $di;
}


    



