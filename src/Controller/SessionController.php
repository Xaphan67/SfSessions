<?php

namespace App\Controller;

use App\Entity\Session;
use App\Repository\SessionRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SessionController extends AbstractController
{
    #[Route('/sessions', name: 'app_session')]
    public function index(SessionRepository $sessionRepository): Response
    {
        // Récupère toutes les sessions et les classe dans l'ordre alphabétique de leur nom
        $sessions = $sessionRepository->findBy([], ["nom" => "ASC"]);

        return $this->render('session/index.html.twig', [
            'sessions' => $sessions,
        ]);
    }

    #[Route('/session/show/{id}', name: 'show_session')]
    public function Show(Session $session) : Response
    {
        return $this->render('session/show.html.twig', [
            'session' => $session
        ]);
    }
}
