<?php
namespace B3\Crawler\Decorator;
use Symfony\Component\DomCrawler\AbstractUriElement as Subject;
use Symfony\Component\DomCrawler\Form as FormCrawler;
use B3\Decorator\AbstractDecorator;
// use Form as FormDecorator;
use \DOMElement;

use Illuminate\Container\Container;
use B3\Decorator\Pool;


class UriElement extends AbstractDecorator
{
    /**
     * 
     *
     * @param \Illuminate\Container\Container $app
     * @param \B3\Decorator\Pool $pool
     * @param \Symfony\Component\DomCrawler\AbstractUriElement $subject
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
        // if($result instanceof FormCrawler){
        //     $result = $this->di->make(FormDecorator::class,['subject' => $result] );
        // }else
        if($result instanceof Subject){
            $result = $this->app->make(Static::class,['subject' => $result] );
        }
        return $result;
    }

    /**
     * 
     *
     * @param string $name
     * @param array $parameters
     * @param string $value     
     * @return DOMElement
     */
    public function createNode(string $name, $parameters=[], ?string $value=null): DOMElement
    {
        $subjectNode = $this->subject->getNode();
        $ownerDoc = $subjectNode->ownerDocument;
        $newNode = $ownerDoc->createElement($name);        
        $newNode->nodeValue = $value;        
        array_walk($parameters, function($value,$attr) use($newNode){
            $newNode->setAttribute($attr,$value);            
        });
        return $newNode;        
    }

    /**
     * Append dom node
     *
     * @param DOMElement $child
     * @return UriElement
     */
    public function appendChild(DOMElement $child) : UriElement
    {
        $subjectNode = $this->subject->getNode();
        $ownerDoc = $subjectNode->ownerDocument;
        $subjectNode->appendChild($child);
        return $this;
    }

    /**
     * 
     *
     * @param string $name
     * @param string|null $value
     * @param array $parameters
     * @return UriElement
     */
    public function append(string $name, $parameters=[], ?string $value=null): UriElement
    {
        $newNode = $this->createNode($name,$parameters,$value);
        return $this->appendChild($newNode);
    }

}