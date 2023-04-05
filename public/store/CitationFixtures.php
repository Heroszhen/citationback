<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\Citation;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\DataFixtures\UserFixtures;

class CitationFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        
        for($i = 0; $i < 100; $i++){
            $citation = new Citation();
            $n = $faker->numberBetween(1, 9);
            $user = $manager->find(User::class,$n);
            $citation
                ->setUser($user)
                ->setMessage($faker->realText(rand(100, 300)))
                ->setCreated(new \DateTime());
            $manager->persist($citation);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class
        );
    }

}