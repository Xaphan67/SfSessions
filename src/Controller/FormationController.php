<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Form\FormationType;
use App\Repository\FormationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FormationController extends AbstractController
{
    #[Route('/formations', name: 'app_formation')]
    public function index(FormationRepository $formationRepository): Response
    {
        // Récupère toutes les formations et les classe dans l'ordre alphabétique de leur nom
        $formations = $formationRepository->findBy([], ["nom" => "ASC"]);

        return $this->render('formation/index.html.twig', [
            'formations' => $formations,
        ]);
    }

    #[Route('/formation/new', name: 'new_formation')]
    #[Route('/formation/edit/{id}', name: 'edit_formation')]
    public function new_edit(Formation $formation = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Instancie une nouvelle formation lors d'un ajout
        if (!$formation) {
            $formation = new Formation();
        }

        // Instancie un formulaire de type Formation
        $form = $this->createForm(FormationType::class, $formation);

        $form->handleRequest($request);

        // Traitement du formulaire s'il est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            $formation = $form->getData();
            // Prpare PDO
            $entityManager->persist($formation);
            // Execute PDO
            $entityManager->flush();

            // Redirige vers la liste des formations
            return $this->redirectToRoute('app_formation');
        }

        return $this->render('formation/new.html.twig', [
            'formAddFormation' => $form,
            'edit' => $formation->getId(),
            'formation' => $formation
        ]);
    }
}
