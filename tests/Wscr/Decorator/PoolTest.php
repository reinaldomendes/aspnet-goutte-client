<?php
namespace Wscr\Decorator;
use \Wscr\Decorator\Pool;
use \Wscr\Decorator\DecoratorInterface;

use PHPUnit\Framework\TestCase;

class __Decorator implements DecoratorInterface
{   
    protected $std;

    public function __construct($std){
        $this->std  = $std;
    }

    public function __call(string $name, array $args = []){

    }

    public function getSubject(){
             
        return $this->std;
    }
}

/**
 * Scrapper test case.
 *
 */
class PoolTest extends TestCase
{
    protected $model;
    protected $subject;
    public function setup():void
    {        
        $this->model = di()->make(Pool::class);
    }

    /**
     * @test
     */
    public function it_should_return_individual_hash_for_objects(){
        $std = new \StdClass();
        $clone = clone $std;
        $refModel = new \ReflectionObject($this->model);
        $refObject = $refModel->getMethod('hashObject');
        $refObject->setAccessible(true);
        $stdId = $refObject->invokeArgs($this->model,[$std]);
        $cloneId = $refObject->invokeArgs($this->model,[$clone]);        
        $this->assertFalse($stdId === $cloneId);
    }

     /**
     * @test
     */
    public function it_should_return_null_when_we_call_hash_object_of_non_objects(){
        
        $refModel = new \ReflectionObject($this->model);
        $refObject = $refModel->getMethod('hashObject');
        $refObject->setAccessible(true);
        $stdId = $refObject->invokeArgs($this->model,[null]);
        $this->assertThat($stdId, $this->isNull());
    }
    
    /** 
     * @test
     */
    public function it_should_register_a_subject_of_decorated(){
        
        $decoratorMock = new __Decorator(new \StdClass());
        $std = $decoratorMock->getSubject();
        $this->model->register($decoratorMock);        
        $result = $this->model->registry($std);

        $this->assertSame($decoratorMock, $result);        
    }

    /** 
    * @test
    */
    public function it_should_return_null_registry_is_call_with_non_object(){            
        $result = $this->model->registry(1);
        $this->assertThat($result, $this->isNull());
    }

     /** 
    * @test
    */
    public function it_should_return_decorator_with_inject(){                    
        $invokableMock = $this->getMockBuilder(\StdClass::class)
                               ->addMethods(['__invoke'])
                               ->getMock();
        $decorator = new __Decorator(new \StdClass());        
        $std = $decorator->getSubject();
        $invokableMock->expects($this->once())
                       ->method('__invoke')
                       ->willReturn($decorator);

        $result = $this->model->inject($std, $invokableMock);

        $this->assertSame($decorator, $result);
        $result = $this->model->inject($std, $invokableMock);
        $this->assertSame($decorator, $result);
    }
    

}