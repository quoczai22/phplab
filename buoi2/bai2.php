<?php
class Student
{
    public $name;
    public $age;

    public function __construct($name, $age)
    {
        $this->name = $name;
        $this->age = $age;
    }

    public function showInfo()
    {
        echo "Name: {$this->name},  Age: {$this->age}<br>";
    }

    public function __destruct()
    {
        echo "Doi tuong da duoc huy<br>";
    }
}


$student1 = new Student("Nguyen Van A", 20);
$student1->showInfo();
