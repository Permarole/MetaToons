<?php

namespace App\DataFixtures;

use App\Entity\Manga;
use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class MangaFixtures extends Fixture implements DependentFixtureInterface
{
    private Slugify $slugify;

    public function __construct(Slugify $slugify) {
        $this->slugify = $slugify;
    }
    public const MANGA = [
        0 => [
            'Full Metal Achemist',
            'Hiromu Arakawa',
            'https://upload.wikimedia.org/wikipedia/en/9/9d/Fullmetal123.jpg',
            'Action',
            false
        ],
        1 => [
            'Dokgo',
            'Meen & Seung Hoon Baek',
            'https://www.anime-gate.net/images-mangas/dokgo.jpg',
            'Action',
            false
        ],
        2 => [
            'Hardcore Leveling Warrior',
            'Sehun Kimset',
            'https://static.tvtropes.org/pmwiki/pub/images/hclw_title_0.jpg',
            'Action',
            false
        ],
        3 => [
            'Shingeki no Kyojin',
            'Hajime Isayama',
            'https://upload.wikimedia.org/wikipedia/en/d/d6/Shingeki_no_Kyojin_manga_volume_1.jpg',
            'Action',
            false
        ],
        4 => [
            'Death Note',
            'Takeshi Obata',
            'https://upload.wikimedia.org/wikipedia/en/6/6f/Death_Note_Vol_1.jpg',
            'Action',
            false
        ],
        5 => [
            'Black Clover',
            'Yuki Tabata',
            'https://d1w7fb2mkkr3kw.cloudfront.net/assets/images/book/lrg/9781/4215/9781421587189.jpg',
            'Action',
            false
        ],
        6 => [
            'Detective Conan',
            'Gosho Aoyama',
            'https://static.fnac-static.com/multimedia/Images/FR/NR/c2/a4/01/107714/1507-1/tsp20210304165441/Detective-Conan.jpg',
            'Action',
            false
        ],
    ];

    public function getOrder(){
        return 2;
    }

    public function getDependencies()
    {
        return [
            GenreFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        foreach (SELF::MANGA as $key => $mangaData) {
            $manga = new Manga();
            $manga->setTitle($mangaData[0]);
            $manga->setAuthor($mangaData[1]);
            $manga->setImage($mangaData[2]);
            $manga->setGenre($this->getReference($mangaData[3]));
            $manga->setOnGoing($mangaData[4]);
            $manga->setSlug($this->slugify->generate($manga->getTitle()));
            $this->addReference('manga_' . $key, $manga);
            $manager->persist($manga);
            $manager->flush();
        }

    }
}
