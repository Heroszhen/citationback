<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\Citation;
use App\Entity\Comment;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\DataFixtures\UserFixtures;
use App\DataFixtures\CitationFixtures;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        
        for($i = 0; $i < 100; $i++){
            $comment = new Comment();
            $n = $faker->numberBetween(1, 9);
            $n2 = $faker->numberBetween(1, 99);
            $user = $manager->find(User::class,$n);
            $citation = $manager->find(Citation::class,$n2);
            $comment
                ->setUser($user)
                ->setCitation($citation)
                ->setMessage($faker->realText(rand(20, 50)))
                ->setCreated(new \DateTime());
            $manager->persist($comment);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
            CitationFixtures::class
        );
    }

}