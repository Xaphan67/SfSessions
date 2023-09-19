<?php

namespace App\Controller;

use App\Entity\Session;
use App\Form\SessionType;
use App\Repository\SessionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/session/new', name: 'new_session')]
    #[Route('/session/edit/{id}', name: 'edit_session')]
    public function new_edit(Session $session = null, Request $request, EntityManagerInterface $entityManager) : Response
    {
        // Instancie une nouvelle session lors d'un ajout
        if (!$session)
        {
            $session = new Session();
        }

        // Instancie un formulaire de type Session
        $form = $this->createForm(SessionType::class, $session);

        $form->handleRequest($request);

        // Traitement du formulaire s'il est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            $session = $form->getData();
            // Prpare PDO
            $entityManager->persist($session);
            // Execute PDO
            $entityManager->flush();
            
            // Redirige vers la lliste des sessions
            return $this->redirectToRoute('app_session');
        }

        return $this->render('session/new.html.twig', [
            'formAddSession' => $form,
            'edit' => $session->getId(),
            'session' => $session
        ]);
    }

    #[Route('/session/show/{id}', name: 'show_session')]
    public function show(Session $session) : Response
    {
        return $this->render('session/show.html.twig', [
            'session' => $session
        ]);
    }
}
