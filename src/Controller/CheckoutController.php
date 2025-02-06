<?php

namespace App\Controller;

use App\Entity\ShoppingCart;
use App\Entity\Order;
use App\Form\OrderType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\VarDumper\Cloner\Data;

final class CheckoutController extends AbstractController
{
    #[Route('/checkout', name: 'app_checkout')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $shoppingCart = $entityManager->getRepository(ShoppingCart::class)->findOneBy(['user' => $user]);
        $cartData = $shoppingCart->getCartData();
        $items = $cartData;
        $total = $shoppingCart->getTotal();

        $order = new Order();
        $form = $this->createForm(orderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $order = $form->getData();
            $order->setItems($items);
            $order->setTotalprice($total);
            $order->setDateoforder(new \DateTime());
            $entityManager->persist($order);
            $entityManager->flush();

            return $this->redirectToRoute('app_checked_out', ["id" => $order->getId()]);
        }
        return $this->render('checkout/index.html.twig', [
            'form' => $form->createView(),
            'order' => $order,
        ]);
    }
    #[Route('/checked-out/{id}', name: 'app_checked_out')]
    public function checkedOut(EntityManagerInterface $entityManager, $id): Response
    {
        $order = $entityManager->getRepository(Order::class)->find($id);
        return $this->render('checkout/checked_out.html.twig', [
            'order' => $order,
        ]);
    }
}
