<?php
namespace B3\Decorator;

use Illuminate\Container\Container;

abstract class AbstractDecorator implements DecoratorInterface
{
    
    protected $subject;

    /** @var Pool */
    protected $decoratorPool;

    /** @var Container */
    protected $app;


    public function __construct(
        Container $app,
        Pool $pool,        
        $subject        
    )
    {
        $this->subject = $subject;    
        $this->decoratorPool = $pool;
        $this->app = $app;
    }

    /**
     * 
     *
     * @param string $name
     * @param array $args
     * @return void
     */
    public function __call(string $name, array $args = [])
    {
        $args = $this->undecorateArgs($name, $args);
        $result = call_user_func_array([$this->subject, $name], $args );
        if($result === $this->subject)
        {
            return $this;
        }
        
        return $this->applyDecorators($result);
    }

    public function __clone()
    {   
        $this->subject = clone $this->subject;
        return $this->withPool($this->subject, function($subject){
            return $this;
        });        
    }
    
   /**
    * Undocumented function
    *
    * @param string $name
    * @param array $args
    * @return array
    */
    protected function undecorateArgs(string $name, array $args = []):array
    {
        $refMethod = new \ReflectionMethod($this->subject, $name);
        $refParameters = $refMethod->getParameters();
        array_walk($args, function(&$value, $index) use($refParameters)
        {
            //ealry return checks            
            if(! $value instanceof DecoratorInterface){
                return;//not decorator
            }

            
            $refParameter = $refParameters[$index] ?? null;
            if(!$refParameter ){
                return;//method received more args than definition
            }

            if(!$refParameter->getClass()){                    
                return;//param has not class hint
            }

            
            $refParamClass = $refParameter->getClass();
            if($refParamClass->implementsInterface(DecoratorInterface::class)){
               return;// param class hint implements decorator
            }

            //
            $value = $value->getSubject();
        });
        return $args;
    }

    /**
     * 
     *
     * @return Mixed
     */
    protected function applyDecorators($result){
        if(is_array($result)){
            return array_map([$this,'applyDecorators'], $result );
        }
           
        return $this->withPool($result, function($result){
            $decorated =  $this->decorate($result);
            return $decorated;
        });
    }

    /**
     * Apply flyweight pattern to avoid use multiple decorators for same object
     *
     * @param Object $result
     * @param Callable $callback
     * @return void
     */
    protected function withPool($result, Callable $callback){
        
        return $this->decoratorPool->inject($result, $callback);
    }

   

    /**
     * 
     *
     * @return Mixed
     */
    protected abstract function decorate($result);
        

    /**
     * 
     *
     * @return Mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }
}