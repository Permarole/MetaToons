<?php

namespace App\Controller;

use App\Entity\Manga;
use App\Entity\User;
use App\Entity\WatchList;
use App\Repository\WatchListRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;

class WatchListController extends AbstractController
{
    /**
     * @Route("/updateWatchList/{manga}", name="update_watch_list")
     * @Security("is_granted('ROLE_USER')")
     */
    public function updateWatchList(
        WatchListRepository $watchListRepository,
        Manga $manga,
        EntityManagerInterface $entityManagerInterface
    ): Response {
        $exist = $watchListRepository->findOneBy(['manga' => $manga]);
        if ($exist === null) {
            $watchList = new WatchList();
            $watchList->setUser($this->getUser());
            $watchList->setManga($manga);
            $entityManagerInterface->persist($watchList);
            $entityManagerInterface->flush();
            return new Response('Insertion complete');
        }

        $entityManagerInterface->remove($exist);
        $entityManagerInterface->flush();
        return new Response('Deletion complete');
    }
}
