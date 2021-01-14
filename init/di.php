<?php





$app = di();

$app -> singleton(Illuminate\Container\Container::class, function() use($app){
    return $app;
});

$app -> singleton(Wscr\Decorator\Pool::class);

$app -> when(Wscr\Crawler\GoutteClient::class)
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
   







// $app -> when(Wscr\Decorator\AbstractDecorator::class)
//      -> needs(Wscr\Decorator\Pool::class)
//      -> give($app->make(Wscr\Decorator\Pool::class));







