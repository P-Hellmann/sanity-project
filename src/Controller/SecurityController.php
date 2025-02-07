<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route(path: '/admin/users', name: 'app_admin_users')]
    public function showUsers(EntityManagerInterface $entityManager, Request $request): Response
    {
       $users = $entityManager->getRepository(User::class)->findAll();
        $searchbar = $this->createForm(SearchType::class, null, ["attr" => ['placeholder' => 'Zoek gebruiker']]);
        $searchbar->handleRequest($request);
        if ($searchbar->isSubmitted() && $searchbar->isValid()) {
            $searchData = $searchbar->getData();
            $queryBuilder = $entityManager->getRepository(User::class)->createQueryBuilder('u');

            if (!empty($searchData['email'])) {
                $queryBuilder->andWhere('u.email LIKE :email')
                    ->setParameter('email', '%' . $searchData['email'] . '%');
            }

            if (!empty($searchData['id'])) {
                $queryBuilder->andWhere('u.id = :id')
                    ->setParameter('id', $searchData['id']);
            }

            if (!empty($searchData['role'])) {
                $queryBuilder->andWhere('u.roles LIKE :role')
                    ->setParameter('role', '%' . $searchData['role'] . '%');
            }

            $users = $queryBuilder->getQuery()->getResult();
        }
        return $this->render('security/admin_users.html.twig', [
            'users' => $users,
            'searchbar' => $searchbar->createView(),
        ]);
    }
}
