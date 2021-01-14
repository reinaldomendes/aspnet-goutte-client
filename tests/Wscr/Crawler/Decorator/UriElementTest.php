<?php

namespace Wscr\Crawler\Decorator;
use PHPUnit\Framework\TestCase;

/**
 * Scrapper test case.
 *
 */
class UriElementTest  extends TestCase
{
    
    /**
     * 
     *
     * @var UriElement
     */
    protected $model;
    /** @var \Symfony\Component\DomCrawler\AbstractUriElement */
    protected $subject;

    public function setup() : void
    {
        $currentUri = "http://localhost/xpto";
        $domDoc = new \DomDocument();
        $node = $domDoc->createElement('img');
        
        
        $this->subject = $this->getMockBuilder(\Symfony\Component\DomCrawler\Image::class)
                               ->setConstructorArgs([$node, $currentUri])                               
                               ->enableProxyingToOriginalMethods()
                               ->addMethods(['getCloned'])
                               ->getMock();
        $this->subject->method('getCloned')->will($this->returnCallback(function(){            
            return clone $this->subject;
        }));

        $this->model = di()->make(UriElement::class, [ 'subject' => $this->subject]);
    }
    public function tearDown() : void
    {
        unset($this->model);
    }

    /**
     * 
     *
     * @test
     */
    public function it_should_return_the_same_subject(){
        $result = $this->model->getSubject();
        $this->assertSame($this->subject,$result);
    }

     /**
     * 
     *
     * @test
     */
    public function it_should_call_subject_method_and_return_decorated(){
        $node = $this->subject->getNode();        
        $this->assertSame($node,$this->model->getNode());
    }
     /**
     * 
     *
     * @test
     */
    public function it_should_decorate_new_instance_of_same_subject(){
        
        $clonedDecorator = $this->model->getCloned();
        
        $this->assertInstanceof(get_class($this->model), $clonedDecorator);
        $this->assertNotSame($this->model, $clonedDecorator);
    }
}
    