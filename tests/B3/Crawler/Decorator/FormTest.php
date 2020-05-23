<?php

namespace B3\Crawler\Decorator;
use PHPUnit\Framework\TestCase;

/**
 * Scrapper test case.
 *
 */
class FormTest  extends TestCase
{
    
    /**
     * 
     *
     * @var Form
     */
    protected $model;
    /** @var \Symfony\Component\DomCrawler\AbstractUriElement */
    protected $subject;

    public function setup() : void
    {
        $currentUri = "http://localhost/xpto";
        $domDoc = new \DomDocument();
        $node = $domDoc->createElement('form');
        
        $this->subject = $this->getMockBuilder(\Symfony\Component\DomCrawler\Form::class)
                               ->setConstructorArgs([$node, $currentUri])                               
                               ->enableProxyingToOriginalMethods()
                               ->addMethods(['getCloned'])
                               ->getMock();
        $this->subject->method('getCloned')->will($this->returnCallback(function(){            
            return clone $this->subject;
        }));

        $this->model = di()->make(Form::class, [ 'subject' => $this->subject]);
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
        
        $self = $this->model->append('textarea', ['name' => 'x']);        
        $self2 = $this->model->append('input',['type' => 'hidden']);
        
        $this->assertInstanceof(get_class($this->model), $self);
        $this->assertSame($self2, $self);
    }
}
    