<?php
namespace B3\Crawler\Decorator;

use Symfony\Component\DomCrawler\Crawler as Subject;
use Symfony\Component\DomCrawler\AbstractUriElement;
use B3\Decorator\AbstractDecorator;
use Symfony\Component\DomCrawler\Form as FormCrawler;
use Illuminate\Container\Container;
use B3\Decorator\Pool;

use B3\Crawler\Decorator\{
    UriElement as ElementDecorator,
    Form as FormDecorator
};

class Crawler extends AbstractDecorator
{

     /**
     * 
     *
     * @param \Illuminate\Container\Container $app
     * @param \B3\Decorator\Pool $pool
     * @param \Symfony\Component\DomCrawler\Crawler $subject
     */
    public function __construct(
        Container $app,
        Pool $pool,        
        Subject $subject
        )
    {
        parent::__construct($app,$pool,$subject);        
    }

    protected function decorate($result)
    {
        if($result instanceof FormCrawler){
            $result = $this->app->make(FormDecorator::class,['subject' => $result] );
        }elseif($result instanceof AbstractUriElement){
            $result = $this->app->make(ElementDecorator::class, ['subject' => $result]);
        }elseif($result instanceof Subject)
        {            
            $result = $this->app->make(\B3\Crawler\Decorator\Crawler::class, ['subject' => $result]);            
        }
        return $result;
    }

}