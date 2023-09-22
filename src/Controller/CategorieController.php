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
    #[Route('/categorie/new', name: 'new_categorie')]
    #[Route('/categorie/edit/{id}', name: 'edit_categorie')]
    public function new_edit(Categorie $categorie = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Instancie une nouvelle Categorie lors d'un ajout
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
            return $this->redirectToRoute('app_module');
        }

        return $this->render('categorie/new.html.twig', [
            'formAddCategorie' => $form,
            'edit' => $categorie->getId(),
            'categorie' => $categorie
        ]);
    }
}
