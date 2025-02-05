<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProductController extends AbstractController
{
    #[Route('/product/{id}', name: 'app_product_show')]
    public function index(EntityManagerInterface $entityManager, Product $product): Response
    {
        $product = $entityManager->getRepository(Product::class)->find($product);
        return $this->render('product/show.html.twig', [
            "product" => $product,
        ]);
    }
}
