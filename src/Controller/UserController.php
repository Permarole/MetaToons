<?php

namespace App\Controller;

use App\Repository\ChapterRepository;
use App\Repository\MangaRepository;
use App\Repository\WatchListRepository;
use App\Service\CalculRelease;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
     * @Route("/user", name="user_")
     * @Security("is_granted('ROLE_USER')")
     */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * 
     */
    public function index(
        WatchListRepository $watchListRepository,
        MangaRepository $mangaRepository,
        ChapterRepository $chapterRepository,
        CalculRelease $calculRelease
    ): Response
    {
        $user = $this->getUser();

        $watchlist = $watchListRepository->findBy(['user' => $user]);

        $mangasInWatchList = [];

        $lastReleased = [];

        foreach ($watchlist as $element) {
            $manga = $mangaRepository->findOneBy(['id' => $element->getManga()->getId()]);
            $lastChapter = $chapterRepository->findLast($manga->getId());
            $new = $calculRelease->isNew($lastChapter->getReleaseDate());
            $mangasInWatchList[] = [$manga, $lastChapter, $new];
            if ($element->getLastRead() != null) {
                $lastChapter = $chapterRepository->findLast($manga->getId());
                
                if ($lastChapter->getId() != $element->getLastRead()){
                    $lastReleased[] = [$manga, $lastChapter, $new];
                }
            }
        }
        return $this->render('user/userDashboard.html.twig', [
            'user' => $user,
            'mangasInWatchList' => $mangasInWatchList,
            'lastReleased' => $lastReleased
        ]);
    }
}
