<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    #[Route('/admin/products', name: 'app_admin_products')]
    public function adminIndex(EntityManagerInterface $entityManager): Response
    {
        // Retrieve a list of all products for admin management
        $products = $entityManager->getRepository(Product::class)->findAll();

        return $this->render('product/admin_products.html.twig', [
            'products' => $products,
        ]);
    }
    
    #[Route('/admin/product/add', name: 'app_admin_product_add')]
    public function add(EntityManagerInterface $entityManager, Request $request): Response
    {
        // Creating a new product instance
        $product = new Product();

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($product);
            $entityManager->flush();
            return $this->redirectToRoute('app_product_show', ['id' => $product->getId()]);
        }

        return $this->render('product/admin_product_add.html.twig', [
            'addProductForm' => $form->createView(),
        ]);
    }

    #[Route('/admin/product/edit/{id}', name: 'app_admin_product_edit')]
    public function edit(EntityManagerInterface $entityManager, Request $request, $id): Response
    {
        $product = $entityManager->getRepository(Product::class)->find($id);
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($product);
            $entityManager->flush();
            // Redirect or return a response after editing
            return $this->redirectToRoute('app_product_show', ['id' => $product->getId()]);
        }

        return $this->render('product/admin_product_edit.html.twig', [
            'editProductForm' => $form->createView(),
        ]);
    }

    #[Route('/admin/product/delete/{id}', name: 'app_admin_product_delete')]
    public function delete(EntityManagerInterface $entityManager, $id): Response
    {
        $product = $entityManager->getRepository(Product::class)->find($id);

        $entityManager->remove($product);
        $entityManager->flush();

        // Redirect or return a response after deletion
        return $this->redirectToRoute('app_admin_products'); // Replace with actual listing route
    }
}
