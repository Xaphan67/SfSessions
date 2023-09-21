<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategorieController extends AbstractController
{
    #[Route('/categorie', name: 'app_categorie')]
    public function index(): Response
    {
        return $this->render('categorie/index.html.twig', [
            'controller_name' => 'CategorieController',
        ]);
    }

    #[Route('/Categorie/new', name: 'new_categorie')]
    #[Route('/Categorie/edit/{id}', name: 'edit_categorie')]
    public function new_edit(Categorie $categorie = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Instancie un nouveau Categorie lors d'un ajout
        if (!$categorie) {
            $categorie = new Categorie();
        }

        // Instancie un formulaire de type categorie
        $form = $this->createForm(CategorieType::class, $categorie);

        $form->handleRequest($request);

        // Traitement du formulaire s'il est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            $categorie = $form->getData();
            // Prpare PDO
            $entityManager->persist($categorie);
            // Execute PDO
            $entityManager->flush();

            // Redirige vers la liste des categories
            return $this->redirectToRoute('app_modules');
        }

        return $this->render('categorie/new.html.twig', [
            'formAddCategorie' => $form,
            'edit' => $categorie->getId(),
            'categorie' => $categorie
        ]);
    }
}
