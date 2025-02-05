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
        $categories = $entityManager->getRepository(Category::class)->findAll();
        return $this->render('category/index.html.twig', [
            "categories" => $categories,
        ]);
    }
    #[Route('/categories/{id}', name: 'app_category_show')]
    public function show(EntityManagerInterface $entityManager, Category $category): Response
    {
        $products = $entityManager->getRepository(Product::class)->findby(['category' => $category]);
        return $this->render('category/show.html.twig', [
            "products" => $products,
            "category" => $category,
        ]);
    }
}
