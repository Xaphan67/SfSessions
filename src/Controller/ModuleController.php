<?php

namespace App\Controller;


use App\Entity\Module;
use App\Form\ModuleType;
use App\Repository\ModuleRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ModuleController extends AbstractController
{
    #[Route('/module', name: 'app_module')]
    public function index(CategorieRepository $categorieRepository, ModuleRepository $moduleRepository, PaginatorInterface $paginator, Request $request): Response
    {
        // Récupère la liste des catégories et modules dans l'ordre alphabétique de leur nom
        $categories = $categorieRepository->findBy([], ["nom" => "ASC"]);
        $modules = $moduleRepository->findBy([], ["nom" => "ASC"]);

        // Crée la pagination pour la liste des modules
        $pagination = $paginator->paginate(
            $modules,
            $request->query->getInt('page', 1), /*page number*/
            5 /*limite par page*/
        );

        return $this->render('module/index.html.twig', [
            'categories' => $categories,
            'pagination' => $pagination
        ]);
    }

    #[Route('/module/new', name: 'new_module')]
    #[Route('/module/edit/{id}', name: 'edit_module')]
    public function new_edit(Module $module = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Vérifie qu'un utilisateur est connecté
        if ($this->getUser()) {
            // Instancie un nouveeau Module lors d'un ajout
            if (!$module) {
                $module = new Module();
            }

            // Instancie un formulaire de type module
            $form = $this->createForm(ModuleType::class, $module);

            $form->handleRequest($request);

            // Traitement du formulaire s'il est soumis et valide
            if ($form->isSubmitted() && $form->isValid()) {
                $module = $form->getData();
                // Prpare PDO
                $entityManager->persist($module);
                // Execute PDO
                $entityManager->flush();

                // Redirige vers la liste des modules
                return $this->redirectToRoute('app_module');
            }

            return $this->render('module/new.html.twig', [
                'formAddModule' => $form,
                'edit' => $module->getId(),
                'module' => $module
            ]);
        }

        return $this->redirectToRoute(('app_login'));
    }

    #[Route('/module/delete/{id}', name: 'delete_module')]
    public function delete(Module $module, EntityManagerInterface $entityManager): Response
    {
        // Vérifie qu'un utilisateur est connecté
        if ($this->getUser()) {
            $entityManager->remove($module);
            $entityManager->flush();

            return $this->redirectToRoute('app_module');
        }

        return $this->redirectToRoute(('app_login'));
    }
}
