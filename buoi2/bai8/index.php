<?php
require_once 'autoload.php';

use App\Models\Person;
use App\Models\Student;

$person = new Person("Trinh Huu Kien Quoc", 20);
$person->introduce();

$student = new Student("Trinh Huu Kien Quoc", 20, "2001240399");
$student->introduce();
