<?php

namespace App\Models;

class Person
{
    private $name;
    private $age;

    public function getName()
    {
        return $this->name;
    }

    public function getAge()
    {
        return $this->age;
    }

    public function setName($name)
    {
        $trimmedName = trim($name);
        if (strlen($trimmedName) == 0) {
            echo "Ten khong duoc de trong<br>";
            return;
        }
        $this->name = $trimmedName;
    }

    public function setAge($age)
    {
        if ($age < 0) {
            echo "Tuoi khong duoc nho hon 0<br>";
            return;
        }
        $this->age = $age;
    }

    public function __construct($name, $age)
    {
        $this->setName($name);
        $this->setAge($age);
    }

    public function introduce()
    {
        echo "Xin chao, toi la {$this->name} va toi {$this->age} tuoi.<br> \n";
    }
}
