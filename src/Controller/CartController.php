<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\ShoppingCart;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class CartController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/cart', name: 'app_cart')]
    public function index(EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        $user = $this->getUser();
        $shoppingCart = $entityManager->getRepository(ShoppingCart::class)->findOneBy(['user' => $user]);
        $cartData = $shoppingCart->getCartData();
        $items = $cartData['items'];
        $total = 0;
        $amountOfItems = 0;
        foreach ($items as $item) {
            $product = $entityManager->getRepository(Product::class)->find($item['product_id']);
            $total += $product->getPrice() * $item['quantity'];
            $amountOfItems += $item['quantity'];
        }
        $session->set('amountOfItems', $amountOfItems);
        $shoppingCart->setTotal($total);
        $entityManager->persist($shoppingCart);
        $entityManager->flush();
        return $this->render('cart/index.html.twig', [
            'items' => $items,
            'shoppingCart' => $shoppingCart,
            'total' => $total,
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/cart/add/{id}', name: 'app_cart_add')]
    public function add(EntityManagerInterface $entityManager, $id): Response
    {
        $user = $this->getUser();
        $shoppingCart = $entityManager->getRepository(ShoppingCart::class)->findOneBy(['user' => $user]);

        if ($shoppingCart) {
            // Decode JSON cart data
            $shoppingCartData = $shoppingCart->getCartData();

            // Ensure cart structure exists
            if (!isset($shoppingCartData['items'])) {
                $shoppingCartData['items'] = [];
            }

            $newItem = [
                'product_id' => $id,
                'product_name' => $entityManager->getRepository(Product::class)->find($id)->getName(),
                'price' => $entityManager->getRepository(Product::class)->find($id)->getPrice(),
                'quantity' => 1,
            ];

            // Check if product already exists in cart
            $found = false;
            foreach ($shoppingCartData['items'] as &$item) { // Use reference to modify array
                if ($item['product_id'] == $newItem['product_id']) {
                    $item['quantity'] += $newItem['quantity'];
                    $found = true;
                    break;
                }
            }

            // If item is not in the cart, add it
            if (!$found) {
                $shoppingCartData['items'][] = $newItem;
            }

            // Encode JSON and save back to database
            $shoppingCart->setCartData($shoppingCartData);
            $entityManager->persist($shoppingCart);
            $entityManager->flush();
        } else {
            // Create a new shopping cart if one doesn't exist
            $newCartData = [
                "items" => [
                    [
                        "product_id" => $id,
                        'product_name' => $entityManager->getRepository(Product::class)->find($id)->getName(),
                        'price' => $entityManager->getRepository(Product::class)->find($id)->getPrice(),
                        "quantity" => 1
                    ]
                ]
            ];

            $shoppingCart = new ShoppingCart();
            $shoppingCart->setUser($user);
            $shoppingCart->setCartData($newCartData);
            $entityManager->persist($shoppingCart);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_cart');
    }
}
