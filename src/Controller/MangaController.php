<?php

namespace App\Controller;

use App\Entity\Manga;
use App\Form\MangaType;
use App\Repository\ChapterRepository;
use App\Repository\MangaRepository;
use App\Repository\WatchListRepository;
use App\Service\CalculRelease;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
     * @Route("/manga", name="manga_")
     */
class MangaController extends AbstractController
{
    /**
     * @Route("/new", name="new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $manga = new Manga();
        $form = $this->createForm(MangaType::class, $manga);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($manga);
            $entityManager->flush();

            return $this->redirectToRoute('manga_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('manga/new.html.twig', [
            'manga' => $manga,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/genre", name="genre")
     */
    public function genre(MangaRepository $mangaRepository): Response
    {

        $mangas = $mangaRepository->findAll();

        return $this->render('manga/index.html.twig', [
            'mangas' => $mangas,
        ]);
    }

    /**
     * @Route("/{slug}", name="show", methods={"GET"})
     */
    public function show(Manga $manga): Response
    {
        return $this->render('manga/show.html.twig', [
            'manga' => $manga,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Manga $manga, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MangaType::class, $manga);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('manga_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('manga/edit.html.twig', [
            'manga' => $manga,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"POST"})
     */
    public function delete(Request $request, Manga $manga, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$manga->getId(), $request->request->get('_token'))) {
            $entityManager->remove($manga);
            $entityManager->flush();
        }

        return $this->redirectToRoute('manga_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->redirectToRoute('manga_index_alpha', ['letter' => 'A']);
    }

    /**
     * @Route("/index/alpha/{letter}", name="index_alpha")
     */
    public function alpha(string $letter,
    MangaRepository $mangaRepository,
    ChapterRepository $chapterRepository,
    WatchListRepository $watchListRepository,
    CalculRelease $calculRelease)
    {
        $allmangas = $mangaRepository->findByLetter($letter);
        $user = $this->getUser();

        $watchList = $watchListRepository->findBy(['user' => $user]);
        
        $mangasOrder = [];
        foreach ($allmangas as $manga) {

            $lastChapter = $chapterRepository->findlast($manga->getId());
            $mangasOrder[$manga->getId()] = $lastChapter->getReleaseDate();
        }

        $letters = [];

        foreach(range('A', 'Z') as $let){
            $letters[] = $let;
        }

        $mangas = [];
        foreach ($mangasOrder as $key => $date) {
            for($i = 0; $i < count($allmangas); $i++){
                if($allmangas[$i]->getId() === $key){
                    $new = $calculRelease->isNew($date);
                    $mangas[] = [$allmangas[$i], $lastChapter, $new]; 
                }
            }
            
        }

        return $this->render('manga/index.html.twig', [
            'mangas' => $mangas,
            'user' => $user,
            'watchList' => $watchList,
            'letters' => $letters,
            'letter' => $letter

        ]);
    }
}
