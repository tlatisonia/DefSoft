<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements FixtureGroupInterface
{
    public function __construct(private UserPasswordHasherInterface $hasher ){
        $this->hasher = $hasher;
    }
        
    
    public function load(ObjectManager $manager): void
    {
         $userB = new User();
         $userB->setPassword($this->hasher->hashPassword($userB,'admin'));
         $userB->setEmail('admin@gmail.com');
         $userB->setRoles(['ROLE_ADMIN']);
         $manager->persist($userB);

         $userA = new User();
         $userA->setPassword($this->hasher->hashPassword($userA,'valideur'));
         $userA->setEmail('valideur@gmail.com');
         $userA->setRoles(['ROLE_valideur']);
         $manager->persist($userA);

         for($i = 0 ;$i <= 5 ; $i++){
            $user = new User();
            $user->setPassword($this->hasher->hashPassword($user,'user'));
            $user->setEmail("user$i@gmail.com");
            $user->setRoles(['ROLE_USER']);
          
            $manager->persist($user);
         }

        $manager->flush();

    }

    public static function getGroups(): array
    {
        return ['user'];
    }
}
