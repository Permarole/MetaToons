<?php

namespace App\DataFixtures;

use App\Entity\Chapter;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ChapterFixtures extends Fixture implements DependentFixtureInterface
{

    public function getOrder(){
        return 3;
    }

    public function getDependencies()
    {
        return [
            GenreFixtures::class,
            MangaFixtures::class
        ];
    }

    public function load(ObjectManager $manager): void
    {
        for($j = 0; $j<7; $j++){
            for($i = 1; $i<13; $i++){
                $date = "$i/23/2003 12:$i:12";
                $releaseDate = new DateTime($date);
                $chapter = new Chapter();
                $chapter->setNumber($i);
                $chapter->setManga($this->getReference('manga_' . $j));
                $chapter->setReleaseDate($releaseDate);
                $chapter->setLast(false);
                $manager->persist($chapter);
            }
        }
        
        $manager->flush();
    }
}
