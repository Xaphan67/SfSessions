<?php

namespace App\Controller;

use App\Entity\Module;
use App\Entity\Session;
use App\Entity\Programme;
use App\Entity\Stagiaire;
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
    public function new_edit(Session $session = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Instancie une nouvelle session lors d'un ajout
        if (!$session) {
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

            // Redirige vers la liste des sessions
            return $this->redirectToRoute('app_session');
        }

        return $this->render('session/new.html.twig', [
            'formAddSession' => $form,
            'edit' => $session->getId(),
            'session' => $session
        ]);
    }

    #[Route('/session/show/{id}', name: 'show_session')]
    public function show(Session $session, SessionRepository $sessionRepository, EntityManagerInterface $entityManager): Response
    {
        // Récupère tous les stagiaires qui ne sont pas inscrits à la session
        $stagiairesNonInscrits = $sessionRepository->findStagiairesNonInscrits($session->getId());

        // Récupère tous les modules qui ne sont pas programmmés pour la session
        $modulesNonProgrammes = $sessionRepository->findModulesNonProgrammes($session->getId());

        return $this->render('session/show.html.twig', [
            'session' => $session,
            'stagiairesNonInscrits' => $stagiairesNonInscrits,
            'modulesNonProgrammes' => $modulesNonProgrammes
        ]);
    }

    #[Route('/session/addStagiaire/{id}/{stagiaire}', name: 'addStagiaire_session')]
    public function addStagiaire(Session $session, Stagiaire $stagiaire, EntityManagerInterface $entityManager): Response
    {
        // Vérifie qu'il reste des places dans la session
        if (count($session->getStagiaires()) < $session->getPlaces())
        {
            // Ajoute le stagiaire à la session
            $session->addStagiaire($stagiaire);
            // Prepare PDO
            $entityManager->persist($session);
            // Execute PDO
            $entityManager->flush();
        }

        // Redirige vers la page de la session
        return $this->redirectToRoute('show_session', ['id' => $session->getId()]);
    }

    #[Route('/session/removeStagiaire/{id}/{stagiaire}', name: 'removeStagiaire_session')]
    public function removeStagiaire(Session $session, Stagiaire $stagiaire, EntityManagerInterface $entityManager): Response
    {
        // Retire le stagiaire de la session
        $session->removeStagiaire($stagiaire);
        // Prepare PDO
        $entityManager->persist($session);
        // Execute PDO
        $entityManager->flush();

        // Redirige vers la page de la session
        return $this->redirectToRoute('show_session', ['id' => $session->getId()]);
    }

    #[Route('/session/addModule/{id}/{module}', name: 'addModule_session')]
    public function addModule(Session $session, Module $module, Request $request,  EntityManagerInterface $entityManager): Response
    {
        // Récupère le nombre de jour pour le programme à ajouter, s'il y en à un
        $nbJours = $request->request->get('duree');

        // Ajoute le programme
        if ($nbJours != null) {
            $programme = new Programme();
            $programme->setSession($session);
            $programme->setModule($module);
            $programme->setNbJours($nbJours);

            // Prpare PDO
            $entityManager->persist($programme);
            // Execute PDO
            $entityManager->flush();
        }

        // Redirige vers la page de la session
        return $this->redirectToRoute('show_session', ['id' => $session->getId()]);
    }

    #[Route('/session/removeProgramme/{id}/{programme}', name: 'removeModule_session')]
    public function removeProgramme(Session $session, Programme $programme, EntityManagerInterface $entityManager): Response
    {
        // Retire le programme de la session
        $session->removeProgramme($programme);
        // Prepare PDO
        $entityManager->persist($session);
        // Execute PDO
        $entityManager->flush();

        // Redirige vers la page de la session
        return $this->redirectToRoute('show_session', ['id' => $session->getId()]);
    }
}
