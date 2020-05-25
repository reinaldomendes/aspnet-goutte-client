<?php
use Illuminate\Container\Container;
$app = new Container();

$app -> singleton(Illuminate\Container\Container::class, function() use($app){
    return $app;
});

$app -> singleton(B3\Decorator\Pool::class);

$app -> when(B3\Crawler\GoutteClient::class)
     -> needs('$subject')
     -> give(function($app){
        // $goutteOptions =  [
        //     'cookies' => true
        // ];
        $goutteOptions = [];    
        return $app->make(
            Goutte\Client::class,
            [
                $goutteOptions
            ]
        );
    });
   







// $app -> when(B3\Decorator\AbstractDecorator::class)
//      -> needs(B3\Decorator\Pool::class)
//      -> give($app->make(B3\Decorator\Pool::class));







