<?php
abstract class Animal
{
    abstract public function makeSound();
}

interface CanRun
{
    public function run();
}

class Dog extends Animal implements CanRun
{
    public function makeSound()
    {
        echo "Woof! Woof!<br>";
    }

    public function run()
    {
        echo "Dog is running...<br>";
    }
}

class Cat extends Animal implements CanRun
{
    public function makeSound()
    {
        echo "Meow! Meow!<br>";
    }

    public function run()
    {
        echo "Cat is running...<br>";
    }
}

$dog = new Dog();
$dog->makeSound();
$dog->run();

$cat = new Cat();
$cat->makeSound();
