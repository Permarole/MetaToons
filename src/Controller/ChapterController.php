<?php

namespace App\Controller;

use App\Entity\Chapter;
use App\Entity\Manga;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChapterController extends AbstractController
{
    /**
     * @Route("/chapter/{slug}/{chapter}", name="chapter_show")
     */
    public function index(Manga $manga, Chapter $chapter): Response
    {
        return $this->render('chapter/index.html.twig', [
            'manga' => $manga,
            'chapter' => $chapter,
        ]);
    }
}
