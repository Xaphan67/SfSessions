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
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SessionController extends AbstractController
{
    #[Route('/sessions', name: 'app_session')]
    public function index(SessionRepository $sessionRepository): Response
    {
        // Vérifie qu'un utilisateur est connecté
        if ($this->getUser()) {
            // Récupère toutes les sessions et les classe dans l'ordre alphabétique de leur nom
            $sessions = $sessionRepository->findBy([], ["nom" => "ASC"]);

            return $this->render('session/index.html.twig', [
                'sessions' => $sessions,
            ]);
        }

        return $this->redirectToRoute(('app_login'));
    }

    #[Route('/session/new', name: 'new_session')]
    #[Route('/session/edit/{id}', name: 'edit_session')]
    public function new_edit(Session $session = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Vérifie qu'un utilisateur est connecté
        if ($this->getUser()) {

            // Instancie une nouvelle session lors d'un ajout
            if (!$session) {
                $session = new Session();
            }

            // Instancie un formulaire de type Session
            $form = $this->createForm(SessionType::class, $session);

            $form->handleRequest($request);

            // Vérifie que le nombre de places dans la session est supérieur au nombre de stagiaires inscrits
            if ($form->isSubmitted() && $form->isValid()) {
                if ($session->getPlaces() - count($session->getStagiaires()) < 0) {

                    // Redirige vers le formulaire
                    return $this->redirectToRoute('edit_session', ['id' => $session->getId()]);
                }
            }

            // Enregistre l'url d'entrée dans une variable en session en cas de modification mais que le formulaire n'est pas soumis
            if ($session && !$form->isSubmitted()) {
                $request->getSession()->set('urlFrom', $request->headers->get('referer'));
            }

            // Traitement du formulaire s'il est soumis et valide
            if ($form->isSubmitted() && $form->isValid()) {
                $session = $form->getData();
                // Prpare PDO
                $entityManager->persist($session);
                // Execute PDO
                $entityManager->flush();

                // Place l'url stockée en session dans une variable $url
                $url = $request->getSession()->get('urlFrom');

                // Retire la variable stockée en session
                $request->getSession()->remove('urlFrom');

                // Redirige vers l'url stockée dans $url
                return $this->redirect($url);
            }

            return $this->render('session/new.html.twig', [
                'formAddSession' => $form,
                'edit' => $session->getId(),
                'session' => $session
            ]);
        }

        return $this->redirectToRoute(('app_login'));
    }

    #[Route('/session/show/{id}', name: 'show_session')]
    public function show(Session $session, SessionRepository $sessionRepository, EntityManagerInterface $entityManager): Response
    {
        // Vérifie qu'un utilisateur est connecté
        if ($this->getUser()) {
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

        return $this->redirectToRoute(('app_login'));
    }

    #[Route('/session/addStagiaire/{id}/{stagiaire}', name: 'addStagiaire_session')]
    public function addStagiaire(Session $session, Stagiaire $stagiaire, EntityManagerInterface $entityManager): Response
    {
        // Vérifie qu'un utilisateur est connecté
        if ($this->getUser()) {
            // Vérifie qu'il reste des places dans la session
            if (count($session->getStagiaires()) < $session->getPlaces()) {
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

        return $this->redirectToRoute(('app_login'));
    }

    #[Route('/session/removeStagiaire/{id}/{stagiaire}', name: 'removeStagiaire_session')]
    public function removeStagiaire(Session $session, Stagiaire $stagiaire, EntityManagerInterface $entityManager): Response
    {
        // Vérifie qu'un utilisateur est connecté
        if ($this->getUser()) {
            // Retire le stagiaire de la session
            $session->removeStagiaire($stagiaire);
            // Prepare PDO
            $entityManager->persist($session);
            // Execute PDO
            $entityManager->flush();

            // Redirige vers la page de la session
            return $this->redirectToRoute('show_session', ['id' => $session->getId()]);
        }

        return $this->redirectToRoute(('app_login'));
    }

    #[Route('/session/addModule/{id}/{module}', name: 'addModule_session')]
    public function addModule(Session $session, Module $module, Request $request,  EntityManagerInterface $entityManager): Response
    {
        // Vérifie qu'un utilisateur est connecté
        if ($this->getUser()) {
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

        return $this->redirectToRoute(('app_login'));
    }

    #[Route('/session/removeProgramme/{id}/{programme}', name: 'removeModule_session')]
    public function removeProgramme(Session $session, Programme $programme, EntityManagerInterface $entityManager): Response
    {
        // Vérifie qu'un utilisateur est connecté
        if ($this->getUser()) {
            // Retire le programme de la session
            $session->removeProgramme($programme);
            // Prepare PDO
            $entityManager->persist($session);
            // Execute PDO
            $entityManager->flush();

            // Redirige vers la page de la session
            return $this->redirectToRoute('show_session', ['id' => $session->getId()]);
        }

        return $this->redirectToRoute(('app_login'));
    }

    #[Route('/session/delete/{id}', name: 'delete_session')]
    public function delete(Session $session, EntityManagerInterface $entityManager): Response
    {
        // Vérifie qu'un utilisateur est connecté
        if ($this->getUser()) {
            $entityManager->remove($session);
            $entityManager->flush();

            return $this->redirectToRoute('app_session');
        }

        return $this->redirectToRoute(('app_login'));
    }
}
