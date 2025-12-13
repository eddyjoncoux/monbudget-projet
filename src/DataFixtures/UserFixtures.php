<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername('eddydevwebb');
        $user->setPassword(1234);
        $user->setRoles(['ROLES_USER']);
        $user->setName('Eddy');
        $manager->persist($user);

        $manager->flush();
    }
}
