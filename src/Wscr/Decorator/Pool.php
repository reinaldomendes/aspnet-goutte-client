<?php
namespace Wscr\Decorator;

class Pool
{

    /** 
     * @var array 
     */
    protected $registry = [];

    /**
     * 
     *
     * @param mixed $object
     * @return string|int
     */
    protected function hashObject($object)
    {
        if(!is_object($object)){
            return null;
        }
        return \spl_object_id($object) . '.' . \spl_object_hash($object);
    }

    /**
     * 
     *
     * @param DecoratorInterface $decorated
     * @return Pool
     */
    public function register(DecoratorInterface $decorated): Pool
    {   
        $hash = $this->hashObject($decorated->getSubject());        
        
        $this->registry[$hash] = $decorated;    
                          
        return $this;
    }

    /**
     * 
     *
     * @param mixed $subject
     * @return DecoratorInterface|null
     */
    public function registry($subject) : ?DecoratorInterface
    {
        $hash = $this->hashObject($subject);        
       
       

        return $this->registry[$hash]  ?? null;   
    }

    /**
     * Apply flyweight pattern to avoid use multiple decorators for same object
     *
     * @param Object $result
     * @param Callable $callback
     * @return void
     */
    public function inject($result, Callable $callback)
    {        
        $decorated = $this->registry($result);

        if($decorated){
            return $decorated;
        }     

        $decorated = $callback($result);        

        if($decorated instanceof DecoratorInterface)
        {
            $this->register($decorated);
        }

        return $decorated;
    }
}