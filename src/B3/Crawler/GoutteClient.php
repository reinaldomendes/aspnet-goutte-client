<?php
namespace B3\Crawler;

use Goutte\Client as HttpClient;
// use Symfony\Component\DomCrawler\Form;
use Symfony\Component\DomCrawler\Crawler;
use B3\Decorator\{
    AbstractDecorator,
    DecoratorInterface,
    Pool
};

/**
 * GuzzleHttp\Client wrapper for asp.net requests
 * 
 */
class GoutteClient extends AbstractDecorator
{
                
    protected function decorate($result){
        if($result instanceof Crawler)
        {            
            $result = $this->app->make(\B3\Crawler\Decorator\Crawler::class, ['subject' => $result]);            
        }
        return $result;
    }



    public function eventSubmit(string $selector, array $fieldValues = [], string $method = null, array $serverParameters = [])
    {
        $crawler = $this->getCrawler();
        $button = $crawler->filter($selector);
        $form = $button->form($fieldValues, $method);
        $nodeButton = $button->getNode(0);        
       
        $form->append('input',[
            'type' => 'hidden',
            'name' => '__EVENTTARGET',
            'value' => $nodeButton->getAttribute('name')
        ]);            

        return $this->submit($form, [], $serverParameters);       
    }
}