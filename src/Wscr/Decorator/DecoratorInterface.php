<?php
namespace Wscr\Decorator;

interface  DecoratorInterface
{   
    public function __call(string $name, array $args = []);
    public function getSubject();
}