<?php

namespace App\Controller;

use App\Entity\Formateur;
use App\Form\FormateurType;
use App\Repository\FormateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FormateurController extends AbstractController
{
    #[Route('/formateur', name: 'app_formateur')]
    public function index(FormateurRepository $formateurRepository): Response
    {
        // Vérifie qu'un utilisateur est connecté
        if ($this->getUser()) {
            // Récupère tout les formateurs et les classe dans l'ordre alphabétique de leur nom
            $formateurs = $formateurRepository->findBy([], ["nom" => "ASC"]);

            return $this->render('formateur/index.html.twig', [
                'formateurs' => $formateurs,
            ]);
        }

        return $this->redirectToRoute(('app_login'));
    }

    #[Route('/formateur/new', name: 'new_formateur')]
    #[Route('/formateur/edit/{id}', name: 'edit_formateur')]
    public function new_edit(Formateur $formateur = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Vérifie qu'un utilisateur est connecté
        if ($this->getUser()) {
            // Instancie un nouveau formateur lors d'un ajout
            if (!$formateur) {
                $formateur = new Formateur();
            }

            // Instancie un formulaire de type Formateur
            $form = $this->createForm(FormateurType::class, $formateur);

            $form->handleRequest($request);

            // Traitement du formulaire s'il est soumis et valide
            if ($form->isSubmitted() && $form->isValid()) {
                $formateur = $form->getData();
                // Prpare PDO
                $entityManager->persist($formateur);
                // Execute PDO
                $entityManager->flush();

                // Redirige vers la lliste des formateurs
                return $this->redirectToRoute('app_formateur');
            }

            return $this->render('formateur/new.html.twig', [
                'formAddFormateur' => $form,
                'edit' => $formateur->getId(),
                'formateur' => $formateur
            ]);
        }

        return $this->redirectToRoute(('app_login'));
    }

    #[Route('/formateur/show/{id}', name: 'show_formateur')]
    public function show(Formateur $formateur): Response
    {
        // Vérifie qu'un utilisateur est connecté
        if ($this->getUser()) {
            return $this->render('formateur/show.html.twig', [
                'formateur' => $formateur
            ]);
        }

        return $this->redirectToRoute(('app_login'));
    }

    #[Route('/formateur/delete/{id}', name: 'delete_formateur')]
    public function delete(Formateur $formateur, EntityManagerInterface $entityManager): Response
    {
        // Vérifie qu'un utilisateur est connecté
        if ($this->getUser()) {
            $entityManager->remove($formateur);
            $entityManager->flush();

            return $this->redirectToRoute('app_formateur');
        }

        return $this->redirectToRoute(('app_login'));
    }
}
