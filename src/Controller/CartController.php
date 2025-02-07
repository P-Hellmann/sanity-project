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
        // Haal de ingelogde gebruiker op
        $user = $this->getUser();

        // Zoek het winkelwagentje van de gebruiker
        $shoppingCart = $entityManager->getRepository(ShoppingCart::class)->findOneBy(['user' => $user]);

        // Haal de winkelwagengegevens op
        $cartData = $shoppingCart->getCartData();
        $items = $cartData['items'];
        $total = 0;
        $amountOfItems = 0;

        // Bereken het totaalbedrag en het aantal producten
        foreach ($items as $item) {
            $product = $entityManager->getRepository(Product::class)->find($item['product_id']);
            $total += $product->getPrice() * $item['quantity'];
            $amountOfItems += $item['quantity'];
        }

        // Sla het aantal producten op in de sessie
        $session->set('amountOfItems', $amountOfItems);

        // Werk het totaalbedrag bij in de database
        $shoppingCart->setTotal($total);
        $entityManager->persist($shoppingCart);
        $entityManager->flush();

        // Toon de winkelwagenpagina
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
            // Haal de huidige winkelwagengegevens op
            $shoppingCartData = $shoppingCart->getCartData();

            // Zorg ervoor dat de structuur bestaat
            if (!isset($shoppingCartData['items'])) {
                $shoppingCartData['items'] = [];
            }

            // Maak een nieuw item aan
            $newItem = [
                'product_id' => $id,
                'product_name' => $entityManager->getRepository(Product::class)->find($id)->getName(),
                'price' => $entityManager->getRepository(Product::class)->find($id)->getPrice(),
                'quantity' => 1,
            ];

            // Controleer of het product al in de winkelwagen zit
            $found = false;
            foreach ($shoppingCartData['items'] as &$item) {
                if ($item['product_id'] == $newItem['product_id']) {
                    $item['quantity'] += $newItem['quantity'];
                    $found = true;
                    break;
                }
            }

            // Voeg het product toe als het nog niet in de winkelwagen zit
            if (!$found) {
                $shoppingCartData['items'][] = $newItem;
            }

            // Werk de winkelwagen bij in de database
            $shoppingCart->setCartData($shoppingCartData);
            $shoppingCart->setUpdatedAt(new \DateTime());
            $entityManager->persist($shoppingCart);
            $entityManager->flush();
        } else {
            // Maak een nieuwe winkelwagen aan als deze nog niet bestaat
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

        // Geef een succesbericht en ga terug naar de winkelwagenpagina
        $this->addFlash('success', $entityManager->getRepository(Product::class)->find($id)->getName() . ' toegevoegd aan winkelwagen.');
        return $this->redirectToRoute('app_cart');
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/cart/remove/{id}', name: 'app_cart_remove')]
    public function remove(EntityManagerInterface $entityManager, $id): Response
    {
        $user = $this->getUser();
        $shoppingCart = $entityManager->getRepository(ShoppingCart::class)->findOneBy(['user' => $user]);

        if (!$shoppingCart) {
            $this->addFlash('error', 'Winkelwagen niet gevonden.');
            return $this->redirectToRoute('app_cart');
        }

        // Haal de winkelwagengegevens op
        $cartData = $shoppingCart->getCartData();
        $items = $cartData['items'];

        // Verwijder of verminder de hoeveelheid van het product
        foreach ($items as $key => $item) {
            if ($item['product_id'] == $id) {
                if ($item['quantity'] > 1) {
                    $items[$key]['quantity'] -= 1;
                } else {
                    unset($items[$key]);
                }
            }
        }

        // Werk de winkelwagen bij en sla deze op in de database
        $cartData['items'] = array_values($items);
        $shoppingCart->setUpdatedAt(new \DateTime());
        $shoppingCart->setCartData($cartData);
        $entityManager->persist($shoppingCart);
        $entityManager->flush();

        // Geef een melding en ga terug naar de winkelwagenpagina
        $this->addFlash('success', $entityManager->getRepository(Product::class)->find($id)->getName() . ' verwijderd uit winkelwagen.');
        return $this->redirectToRoute('app_cart');
    }
}
