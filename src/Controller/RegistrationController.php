<?php

namespace App\Controller;

use App\Entity\ShoppingCart;
use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();
            $confirmPassword = $form->get('confirmPassword')->getData();

            if ($plainPassword !== $confirmPassword) {
                $this->addFlash('error', 'Wachtwoorden komen niet overeen!');
            } else {
                // encode the plain password
                $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

                $entityManager->persist($user);
                $entityManager->flush();

                // Zoek het winkelwagentje van de gebruiker
                $shoppingCart = $entityManager->getRepository(ShoppingCart::class)->findOneBy(['user' => $user]);
                if (!$shoppingCart) {
                    $shoppingCart = new ShoppingCart();
                    $shoppingCart->setUser($user);
                    $shoppingCart->setCartData(['items' => []]);
                    $entityManager->persist($shoppingCart);
                    $entityManager->flush();
                }
            }

            // do anything else you need here, like send an email

            return $security->login($user, 'form_login', 'main');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}
