<?php

namespace App\Controller;

use App\Entity\Stagiaire;
use App\Form\StagiaireType;
use App\Repository\StagiaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StagiaireController extends AbstractController
{
    #[Route('/stagiaires', name: 'app_stagiaire')]
    public function index(StagiaireRepository $stagiaireRepository): Response
    {
        // Récupère tout les stagiaires et les classe dans l'ordre alphabétique de leur nom
        $stagiaires = $stagiaireRepository->findBy([], ["nom" => "ASC"]);

        return $this->render('stagiaire/index.html.twig', [
            'stagiaires' => $stagiaires,
        ]);
    }

    #[Route('/stagiaire/new', name: 'new_stagiaire')]
    #[Route('/stagiaire/edit/{id}', name: 'edit_stagiaire')]
    public function new_edit(Stagiaire $stagiaire = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Instancie un nouveau stagiaire lors d'un ajout
        if (!$stagiaire) {
            $stagiaire = new Stagiaire();
        }

        // Instancie un formulaire de type Stagiaire
        $form = $this->createForm(StagiaireType::class, $stagiaire);

        $form->handleRequest($request);

        // Traitement du formulaire s'il est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            $stagiaire = $form->getData();
            // Prpare PDO
            $entityManager->persist($stagiaire);
            // Execute PDO
            $entityManager->flush();

            // Redirige vers la lliste des stagiaires
            return $this->redirectToRoute('app_stagiaire');
        }

        return $this->render('stagiaire/new.html.twig', [
            'formAddStagiaire' => $form,
            'edit' => $stagiaire->getId(),
            'stagiaire' => $stagiaire
        ]);
    }

    #[Route('/stagiaire/show/{id}', name: 'show_stagiaire')]
    public function show(Stagiaire $stagiaire): Response
    {
        return $this->render('stagiaire/show.html.twig', [
            'stagiaire' => $stagiaire
        ]);
    }

    #[Route('/stagiaire/delete/{id}', name: 'delete_stagiaire')]
    public function delete(Stagiaire $stagiaire, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($stagiaire);
        $entityManager->flush();

        return $this->redirectToRoute('app_stagiaire');
    }
}
