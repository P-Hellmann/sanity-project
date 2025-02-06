<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\ShoppingCart;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        $user = $this->getUser();
        $shoppingCart = $entityManager->getRepository(ShoppingCart::class)->findOneBy(['user' => $user]);
        $cartData = $shoppingCart->getCartData();
        $items = $cartData['items'];
        $amountOfItems = 0;
        foreach ($items as $item) {
            $amountOfItems += $item['quantity'];
        }
        $session->set('amountOfItems', $amountOfItems);
        return $this->render('home/index.html.twig', [

        ]);
    }
    #[Route('/about', name: 'app_about')]
    public function about(): Response
    {
        return $this->render('home/about.html.twig', [

        ]);
    }
}
