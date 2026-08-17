<?php

namespace App\Models;

class Student extends Person
{
    private $studentID;

    public function getStudentID()
    {
        return $this->studentID;
    }

    public function setStudentID($studentID)
    {
        $trimmedStudentID = trim($studentID);
        if (strlen($trimmedStudentID) == 0) {
            echo "Ma sinh vien khong duoc de trong<br>";
            return;
        }
        $this->studentID = $trimmedStudentID;
    }

    public function __construct($name, $age, $studentID)
    {
        parent::__construct($name, $age);
        $this->setStudentID($studentID);
    }

    public function introduce()
    {
        echo "Xin chao, toi la {$this->getName()} va toi {$this->getAge()} tuoi. Ma sinh vien cua toi la {$this->getStudentID()}.<br> \n";
    }
}
