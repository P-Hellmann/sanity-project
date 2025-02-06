<?php

namespace App\Controller;

use App\Entity\ShoppingCart;
use App\Entity\Order;
use App\Form\AdminOrderType;
use App\Form\OrderType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\VarDumper\Cloner\Data;

final class CheckoutController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/checkout', name: 'app_checkout')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Haal de ingelogde gebruiker op
        $user = $this->getUser();

        // Haal het winkelwagentje van de gebruiker op
        $shoppingCart = $entityManager->getRepository(ShoppingCart::class)->findOneBy(['user' => $user]);
        $cartData = $shoppingCart->getCartData();
        $items = $cartData;
        $total = $shoppingCart->getTotal();

        // Maak een nieuw order object aan
        $order = new Order();

        // Maak het formulier voor de bestelling aan
        $form = $this->createForm(orderType::class, $order);
        $form->handleRequest($request);

        // Controleer of het formulier is ingediend en geldig is
        if ($form->isSubmitted() && $form->isValid()) {
            $order = $form->getData();
            $order->setUser($user);
            $order->setItems($items);
            $order->setTotalprice($total);
            $order->setDateoforder(new \DateTime());

            // Sla de bestelling op in de database
            $entityManager->persist($order);
            $entityManager->flush();

            // Redirect naar de bevestigingspagina
            return $this->redirectToRoute('app_checked_out', ["id" => $order->getId()]);
        }

        // Render de checkoutpagina met het formulier
        return $this->render('checkout/index.html.twig', [
            'form' => $form->createView(),
            'order' => $order,
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/checked-out/{id}', name: 'app_checked_out')]
    public function checkedOut(EntityManagerInterface $entityManager, $id): Response
    {
        // Haal de bestelling op uit de database
        $order = $entityManager->getRepository(Order::class)->find($id);

        // Controleer of de bestelling bij de ingelogde gebruiker hoort
        if ($order->getUser() !== $this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        // Render de bevestigingspagina van de bestelling
        return $this->render('checkout/checked_out.html.twig', [
            'order' => $order,
        ]);
    }


    #[IsGranted('ROLE_USER')]
    #[Route('/orders', name: 'app_orders')]
    public function orders(EntityManagerInterface $entityManager): Response
    {
        // Haal de ingelogde gebruiker op
        $user = $this->getUser();

        // Haal alle bestellingen van de ingelogde gebruiker op
        $orders = $user->getOrders();

        // Render de pagina met alle bestellingen
        return $this->render('checkout/orders.html.twig', [
            'orders' => $orders,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/orders', name: 'app_admin_orders')]
    public function adminOrders(EntityManagerInterface $entityManager): Response
    {
        // Haal alle bestellingen op uit de database
        $orders = $entityManager->getRepository(Order::class)->findAll();

        // Render de administratiepagina met alle bestellingen
        return $this->render('checkout/admin_orders.html.twig', [
            'orders' => $orders,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/orders/edit/{id}', name: 'app_admin_orders_edit')]
    public function editOrder(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        // Haal de bestelling op uit de database
        $order = $entityManager->getRepository(Order::class)->find($id);

        // Controleer of de bestelling bestaat
        if (!$order) {
            throw $this->createNotFoundException('Bestelling niet gevonden.');
        }

        // Maak het formulier voor het bewerken van de bestelling aan
        $form = $this->createForm(AdminOrderType::class, $order);
        $form->handleRequest($request);

        // Controleer of het formulier is ingediend en geldig is
        if ($form->isSubmitted() && $form->isValid()) {
            // Sla de wijzigingen op in de database
            $entityManager->flush();

            // Redirect naar de administratiepagina
            return $this->redirectToRoute('app_admin_orders');
        }

        // Render de bewerkingspagina met het formulier
        return $this->render('checkout/admin_order_edit.html.twig', [
            'form' => $form->createView(),
            'order' => $order,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/orders/delete/{id}', name: 'app_admin_orders_delete', methods: ['POST'])]
    public function deleteOrder(EntityManagerInterface $entityManager, $id): Response
    {
        // Haal de bestelling op uit de database
        $order = $entityManager->getRepository(Order::class)->find($id);

        // Controleer of de bestelling bestaat
        if (!$order) {
            throw $this->createNotFoundException('Bestelling niet gevonden.');
        }

        // Verwijder de bestelling en sla de wijzigingen in de database op
        $entityManager->remove($order);
        $entityManager->flush();

        // Redirect naar de administratiepagina
        return $this->redirectToRoute('app_admin_orders');
    }
}
