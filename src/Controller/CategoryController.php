<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class CategoryController extends AbstractController
{
    #[Route('/categories', name: 'app_categories')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Haal alle categorieën op uit de database
        $categories = $entityManager->getRepository(Category::class)->findAll();

        // Render de categoriepagina met de opgehaalde categorieën
        return $this->render('category/index.html.twig', [
            "categories" => $categories,
        ]);
    }

    #[Route('/categories/{id}', name: 'app_category_show')]
    public function show(EntityManagerInterface $entityManager, Category $category): Response
    {
        // Haal alle producten op die bij de geselecteerde categorie horen
        $products = $entityManager->getRepository(Product::class)->findby(['category' => $category]);

        // Render de categoriepagina met de producten en de categoriegegevens
        return $this->render('category/show.html.twig', [
            "products" => $products,
            "category" => $category,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/categories', name: 'app_admin_categories')]
    public function adminIndex(EntityManagerInterface $entityManager): Response
    {
        // Haal alle categorieën op uit de database
        $categories = $entityManager->getRepository(Category::class)->findAll();

        // Render de admincategoriepagina met de opgehaalde categorieën
        return $this->render('category/admin_categories.html.twig', [
            "categories" => $categories,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/categories/{id}/edit', name: 'app_admin_category_edit')]
    public function edit(EntityManagerInterface $entityManager, Request $request, $id): Response
    {
        $category = $entityManager->getRepository(Category::class)->find($id);

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_admin_categories');
        }

        return $this->render('category/admin_edit_category.html.twig', [
            "editCategoryForm" => $form->createView(),
            "category" => $category,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/categories/{id}/delete', name: 'app_admin_category_delete', methods: ['POST'])]
    public function delete(EntityManagerInterface $entityManager, $id): Response
    {
        // Verwijder de geselecteerde categorie uit de database
        $category = $entityManager->getRepository(Category::class)->find($id);
        $entityManager->remove($category);
        $entityManager->flush();

        // Redirect terug naar de administratiepagina voor categorieën
        return $this->redirectToRoute('app_admin_categories');
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/categories/add', name: 'app_admin_category_add')]
    public function add(EntityManagerInterface $entityManager, Request $request): Response
    {
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_categories');
        }

        return $this->render('category/admin_add_category.html.twig', [
            "addCategoryForm" => $form->createView(),
        ]);
    }
}
