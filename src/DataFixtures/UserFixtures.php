<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $user = new User();
        // $user->setPassword($this->passwordEncoder->encodePassword(
        //     $user,
        //     'aaaaaaaa'
        // ));
        $user 
            ->setEmail("aaa@gmail.com")
            ->setPassword("aaaaaaaa")
            ->setLastname("Héros")
            ->setFirstname("Zhen")
            ->setRoles(["ROLE_USER", "ROLE_ADMIN"])
            ->setCreated(new \DateTime());
        $manager->persist($user);
        for($i = 0;$i < 10;$i++){
            $tab = explode(" ",$faker->name());
            $user = new User();
            // $user->setPassword($this->passwordEncoder->encodePassword(
            //     $user,
            //     'aaaaaaaa'
            // ));
            $user 
                ->setEmail($faker->email())
                ->setPassword("aaaaaaaa")
                ->setLastname($tab[1])
                ->setFirstname($tab[0])
                ->setCreated(new \DateTime());
            $manager->persist($user);
        }
        $manager->flush();
    }
}