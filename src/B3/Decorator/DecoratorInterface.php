<?php
namespace B3\Decorator;

interface  DecoratorInterface
{   
    public function __call(string $name, array $args = []);
    public function getSubject();
}