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
        $categories = $categorieRepository->findBy([], ["nom" => "ASC"]);
        $modules = $moduleRepository->findBy([], ["nom" => "ASC"]);

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
            return $this->redirectToRoute('app_modules');
        }

        return $this->render('module/new.html.twig', [
            'formAddModule' => $form,
            'edit' => $module->getId(),
            'module' => $module
        ]);
    }
}
