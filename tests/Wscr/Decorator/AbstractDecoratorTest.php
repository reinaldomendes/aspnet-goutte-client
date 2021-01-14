<?php
namespace Wscr\Decorator;
use PHPUnit\Framework\TestCase;
class _HACK_Subject extends \StdClass
{
    public  function setObjects(\StdClass $value,DecoratorInterface $value2, int $int,$mixed){
        return $this;
    }
   
}
/**
 * Scrapper test case.
 *
 */
class AbstractDecoratorTest extends TestCase
{
    protected $model;
    protected $subject;
    public function setup():void
    {
        $fnCreateMock = function($subject) use(&$fnCreateMock){
            $di = di();
            $pool = $di->get(Pool::class);
            $mockBuilder = $this->getMockBuilder(AbstractDecorator::class);
            $mock = $mockBuilder->setConstructorArgs([$di, $pool,$subject])
                ->setMethods(['decorate'])
                ->getMock();

                $mock->method('decorate')
                        ->will($this->returnCallback(function($result) use($fnCreateMock){
                            if(is_object($result)){
                                return $fnCreateMock($result);
                            }
                            return $result;
                        }));
            return $mock;
        };
        $fnCloneSubject = function(){
            return clone $this->subject;
        };
        $subjectMockBuilder = $this->getMockBuilder(_HACK_Subject::class)
                                        // ->disableOriginalConstructor()
                                        // ->disableOriginalClone()
                                        // ->disableArgumentCloning()
                                        // ->disallowMockingUnknownTypes()
                                        ;
        $subjectMockBuilder
            ->setMethods(['setObjects'])
            ->addMethods([
            'getInt',
            'getSelf',
            'getObject',
            'getArrayOfObjects',
            'getArrayOfSameObjects',            
        ]);
        
        $this->subject = $subjectMockBuilder->getMock();
        $this->subject->method('getInt')->willReturn(1);
        $this->subject->method('getSelf')->willReturn($this->subject);
        $this->subject->method('getObject')->will($this->returnCallback($fnCloneSubject));
        $this->subject->method('getArrayOfObjects')->will($this->returnCallback(function() use($fnCloneSubject){                        
            return [$fnCloneSubject(), $fnCloneSubject(),$fnCloneSubject()];
        }));
        $this->subject->method('getArrayOfSameObjects')->will($this->returnCallback(function(){
            $clonedSubject = clone $this->subject;
            return [$clonedSubject, $clonedSubject, $clonedSubject];
        }));   
        $this->model = $fnCreateMock($this->subject);
    }
   

      /**
     * 
     *
     * @test
     */
    public function model_shoud_be_a_decorator(){
        $this->assertInstanceOf(DecoratorInterface::class, $this->model);
    }
    /**
     * 
     *
     * @test
     */
    public function it_should_pass_when_method_returns_int(){
        $this->assertEquals($this->model->getInt(), $this->subject->getInt());
    }
     /**
     * 
     *
     * @test
     */
    public function it_should_return_same_decorator_when_subject_returns_self(){
        
        $this->assertSame($this->model->getSelf(), $this->model);
    }

     /**
     * 
     *
     * @test
     */
    public function it_should_return_new_decorator_when_subject_returns_other_object(){
        
        $this->assertNotSame($this->model->getObject(), $this->model);
    }

    
     /**
     * 
     *
     * @test
      */
    public function it_should_clone_subject_when_clone_decorator()
    {                
        $cloned = clone $this->model;
        $this->assertNotSame($cloned->getSubject(), $this->model->getSubject());
    }


    /**
     * 
     *
     * @test
     */
    public function it_should_clean_args_when_subject_method_has_type_hints(){
        
               
        //mock setObject
        $this->subject->method('setObjects')
            ->with(
                $this->equalTo($this->model->getSubject()),
                $this->equalTo($this->model),
                $this->isType('int'),
                $this->anything(),
                $this->equalTo($this->model)
            )
            ->willReturn($this->subject);

        $result = $this->model->setObjects(
            $this->model, 
            $this->model,
            1,
            $this->model, 
            $this->model 
        );
        $this->assertSame($result, $this->model);
    }



   






      /**
     * 
     *
     * @test
     */
    public function it_should_return_individual_decorators_when_subject_returns_array_of_individual_objects(){
        
        $objects = $this->model->getArrayOfObjects();
        
        
        array_reduce($objects, function($acc,$next){
            $acc = $acc ?? clone $next;
            $this->assertInstanceOf(DecoratorInterface::class, $acc);
            $this->assertInstanceOf(DecoratorInterface::class, $next);
            $this->assertNotSame($acc,$next);            
            return $acc;
        });
        
    }


      /**
     * 
     *
     * @test
     */
    public function it_should_return_same_decorators_when_subject_returns_array_of_same_objects(){
        
        $objects = $this->model->getArrayOfSameObjects();
        
        
        array_reduce($objects, function($acc,$next){
            $acc = $acc ?? $next;
            $this->assertInstanceOf(DecoratorInterface::class, $acc);
            $this->assertInstanceOf(DecoratorInterface::class, $next);
            $this->assertSame($acc,$next);            
            return $acc;
        });
        
    }

}