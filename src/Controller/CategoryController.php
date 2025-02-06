<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

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
}
