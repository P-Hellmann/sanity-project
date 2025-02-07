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

final class HomeController extends AbstractController
{
    // Route voor de homepage
    #[Route('/', name: 'app_home')]
    public function index(EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        if ($this->getUser()) {
            // Haal de huidige gebruiker op
            $user = $this->getUser();

            // Zoek het winkelwagentje van de gebruiker in de database
            $shoppingCart = $entityManager->getRepository(ShoppingCart::class)->findOneBy(['user' => $user]);
            if (!$shoppingCart) {
                $shoppingCart = new ShoppingCart();
                $shoppingCart->setUser($user);
                $shoppingCart->setCartData(['items' => []]);
                $entityManager->persist($shoppingCart);
                $entityManager->flush();
            }

            // Haal de gegevens van het winkelwagentje op
            $cartData = $shoppingCart->getCartData();

            // Haal de items uit het winkelwagentje
            $items = $cartData['items'];

            // Initialiseer de hoeveelheid items in het winkelwagentje
            $amountOfItems = 0;

            // Loop door alle items om de totale hoeveelheid te berekenen
            foreach ($items as $item) {
                $amountOfItems += $item['quantity'];
            }

            // Zet de hoeveelheid items in de sessie
            $session->set('amountOfItems', $amountOfItems);
        }

        // Render de homepage weergave
        return $this->render('home/index.html.twig', [
            // Je kunt hier extra data toevoegen om naar de Twig template te sturen
        ]);
    }

    // Route voor de 'over ons' pagina
    #[Route('/about', name: 'app_about')]
    public function about(): Response
    {
        // Render de 'over ons' pagina
        return $this->render('home/about.html.twig', [
            // Je kunt hier extra data toevoegen voor de Twig template
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin', name: 'app_admin')]
    public function adminPanel(): Response
    {
        // Render de admin panel pagina
        return $this->render('home/admin_panel.html.twig', [
            // Voeg hier data toe voor de Twig template
        ]);
    }
}
