<?php

namespace App\Controller;

use App\Repository\SessionRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(SessionRepository $sessionRepository): Response
    {
        // Récupère la liste des sessions en cours
        $sessionsEnCours = $sessionRepository->findSessionsEnCours();

        // Récupère la liste des sessions à venir
        $sessionsAVenir = $sessionRepository->findSessionsAVenir();

        // Récupère la liste des sessions passées
        $sessionsPasses = $sessionRepository->findSessionsPassees();

        return $this->render('home/index.html.twig', [
            'sessionsEnCours' => $sessionsEnCours,
            'sessionsAVenir' => $sessionsAVenir,
            'sessionsPasses' => $sessionsPasses
        ]);
    }
}
