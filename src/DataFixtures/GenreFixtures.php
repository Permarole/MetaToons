<?php

namespace App\DataFixtures;

use App\Entity\Genre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class GenreFixtures extends Fixture
{
    public const NAME = [
        'Drama',
        'Action',
        'Fantastique',
        'Tranche de vie',
        'Romance',
        'Aventure',
        'Dark Fantasy'
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::NAME as $genreName) {
            $genre = new Genre();
            $genre->setName($genreName);
            $this->addReference($genreName, $genre);
            $manager->persist($genre);
        }
        $manager->flush();
    }

    public function getOrder(){
        return 1;
    }
}