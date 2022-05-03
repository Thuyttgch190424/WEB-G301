<?php

namespace App\DataFixtures;

use App\Entity\Student;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class StudentFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $student = new Student;
        $student->setName("Nguyen Xuan Quyen");
        $student->setEmail("quyennxgch190732@fpt.edu.vn");
        $student->setPhone("0379172187");
        $student->setImage("https://www.nssi.com/media/wysiwyg/images/2.jpg");
        $student->setBirthday(\DateTime::createFromFormat('Y-m-d','2001-03-06'));

        $manager->flush();
    }
}
