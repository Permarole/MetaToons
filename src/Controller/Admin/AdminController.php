<?php

namespace App\Controller\Admin;

use App\Entity\Genre;
use App\Entity\Manga;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractDashboardController
{

    public function __construct(AdminUrlGenerator $adminUrlGenerator)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    /**
     * @Route("/admin", name="admin")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function index(): Response
    {
        return $this->redirect($this->adminUrlGenerator->setController(MangaCrudController::class)->generateUrl());
    }


    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('MetaToons')
            ->setTitle('<img src="https://upload.wikimedia.org/wikipedia/fr/thumb/7/72/Mangas_%282015%29.svg/1200px-Mangas_%282015%29.svg.png" alt="MetaToons" width="60" height="60">')
            // ->setFaviconPath('/images/favico.png')
            ;
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::section('Menu'),
            MenuItem::linktoRoute('Retour vers MetaToons', 'fas fa-home', 'home'),
            // MenuItem::linkToLogout('Déconnexion', 'fa fa-sign-out'),
            MenuItem::linkToCrud('Les mangas', "fa fa-file-text", Manga::class),
            MenuItem::linkToCrud('Les genres', "fa fa-file-text", Genre::class),
            // MenuItem::section('Partenaire'),
            // MenuItem::linkToCrud('Vos partenaire', "fas fa-comments", Partner::class),
            // MenuItem::linkToCrud('Ajouter un partenaire', 'fa fa-tags', Partner::class)
            //     ->setAction('new'),
            // MenuItem::section('Utilisateurs'),
            // MenuItem::linkToCrud('Utilisateurs', "fa fa-file-text", User::class),
            // MenuItem::section('Avis'),
            // MenuItem::linktoRoute('Génerer des avis aléatoires', 'fas fa-comment', 'rating'),
        ];
    }
}
