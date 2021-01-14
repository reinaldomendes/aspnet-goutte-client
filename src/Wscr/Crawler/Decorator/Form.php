<?php
namespace Wscr\Crawler\Decorator;
use Symfony\Component\DomCrawler\Form as Subject;
use Wscr\Decorator\AbstractDecorator;
use \DOMElement;
use Symfony\Component\DomCrawler\Field\{
    InputFormField,
    ChoiceFormField,
    TextareaFormField,
    FileFormField,
    FormField
};

use Illuminate\Container\Container;
use Wscr\Decorator\Pool;

class Form extends UriElement
{

    /**
     * 
     *
     * @param \Illuminate\Container\Container $app
     * @param \Wscr\Decorator\Pool $pool
     * @param \Symfony\Component\DomCrawler\Form $subject
     */
    public function __construct(
        Container $app,
        Pool $pool,        
        Subject $subject
        )
    {
        parent::__construct($app,$pool,$subject);        
    }

    /**
     * Append dom node
     *
     * @param DOMElement $child
     * @return UriElement
     */
    public function appendChild(DOMElement $child) : UriElement
    {
        parent::appendChild($child);
        $formInput = $this->createFormInput($child);
        if($formInput){
            $this->getSubject()->set($formInput);
        }
        return $this;
    }

    /**
     * @param DOMElement
     * @return FormField
     */
    
    protected function createFormInput(DOMElement $child) : ?FormField
    {
        $formInput = null;       
        $inputClassHash = [
            'file' => FileFormField::class,
            'radio' => ChoiceFormField::class,
            'checkbox' => ChoiceFormField::class,
        ];        
        switch(strtolower($child->nodeName)){
            case 'input':
                $class = $inputClassHash[strtolower($child->getAttribute('type'))] ?? InputFormField::class;
                $formInput = $this->app->make($class, ['node' => $child]);
            break;
            case 'textarea':
                $formInput = $this->app->make(TextareaFormField::class, ['node' => $child]);
            break;
        }
        return $formInput;
    }

   

}