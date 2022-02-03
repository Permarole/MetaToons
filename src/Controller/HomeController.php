<?php

namespace App\Controller;

use App\Entity\Chapter;
use App\Repository\ChapterRepository;
use App\Repository\GenreRepository;
use App\Repository\MangaRepository;
use App\Repository\WatchListRepository;
use App\Service\CalculRelease;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * 
     */
    public function index(
        MangaRepository $mangaRepository,
        GenreRepository $genreRepository,
        ChapterRepository $chapterRepository,
        CalculRelease $calculRelease,
        WatchListRepository $watchListRepository
    ): Response {

        $user = $this->getUser();

        $watchList = $watchListRepository->findBy(['user' => $user]);
        
        $allmangas = $mangaRepository->findAll();
        $genres = $genreRepository->findAll();
        $mangasOrder = [];
        foreach ($allmangas as $manga) {

            $lastChapter = $chapterRepository->findlast($manga->getId());
            $mangasOrder[$manga->getId()] = $lastChapter->getReleaseDate();
        }

        arsort($mangasOrder);

        $mangas = [];
        foreach ($mangasOrder as $key => $date) {
            for($i = 0; $i < count($allmangas); $i++){
                if($allmangas[$i]->getId() === $key){
                    $new = $calculRelease->isNew($date);
                    $mangas[] = [$allmangas[$i], $lastChapter, $new]; 
                }
            }
            
        }

        return $this->render('home/index.html.twig', [
            'mangas' => $mangas,
            'genres' => $genres,
            'user' => $user,
            'watchList' => $watchList
        ]);
    }

    public function sorting(array $a, array $b)
    {
        if ($a['lastChapterRelease'] === $b['lastChapterRelease']) {
            return 0;
        }
        $a['lastChapterRelease'] < $b['lastChapterRelease'] ? -1 : 1;
    }
}
