<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct (UserPasswordHasherInterface $hasher) {
        $this->hasher = $hasher;
    }
    public function load(ObjectManager $manager): void
    {
    
        //tạo tài khoản test cho admin
        $admin = new User;
        $admin->setUsername("admin");
        $admin->setPassword($this->hasher->hashPassword($admin,"123456"));
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPhone("0912345678");
        $manager->persist($admin);

        //tạo tài khoản test cho user
        $user = new User;
        $user->setUsername("user");
        $user->setPassword($this->hasher->hashPassword($user,"123456"));
        $user->setRoles(['ROLE_USER']);
        $user->setPhone("0913456789");
        $manager->persist($user);

        //tạo tài khoản test cho staff
        $staff = new User;
        $staff->setUsername("staff");
        $staff->setPassword($this->hasher->hashPassword($staff,"123456"));
        $staff->setRoles(['ROLE_STAFF']);
        $staff->setPhone("0988888888");
        $manager->persist($staff);

        $manager->flush();
    }
}
