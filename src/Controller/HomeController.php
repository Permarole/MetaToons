<?php

namespace App\Controller;

use App\Entity\Chapter;
use App\Repository\ChapterRepository;
use App\Repository\GenreRepository;
use App\Repository\MangaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(
        MangaRepository $mangaRepository,
        GenreRepository $genreRepository,
        ChapterRepository $chapterRepository
    ): Response {
        $allmangas = $mangaRepository->findAll();
        $genres = $genreRepository->findAll();
        $mangasOrder = [];
        foreach ($allmangas as $manga) {

            $lastChapter = $chapterRepository->findlast($manga->getId());
            $mangasOrder[$manga->getId()] = $lastChapter[0]->getReleaseDate();
        }

        arsort($mangasOrder);

        $mangas = [];
        foreach ($mangasOrder as $key => $date) {
            for($i = 0; $i < count($allmangas); $i++){
                if($allmangas[$i]->getId() === $key){
                    $mangas[] = [$allmangas[$i], $date]; 
                }
            }
            
        }

        return $this->render('home/index.html.twig', [
            'mangas' => $mangas,
            'genres' => $genres,
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
