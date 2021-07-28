<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index(): Response
    {
        return $this->render('user/login.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher): Response
    {
        $user = new User();
        $formulaire = $this->createForm(RegisterType::class, $user);
        $formulaire->handleRequest($request);

        if ($formulaire->isSubmitted() && $formulaire->isValid()) {
            $hashedPassword = $hasher->hashPassword($user, $user->getPassword());

            $user->setPassword($hashedPassword);
            $manager->persist($user);
            $manager->flush();
        return  $this->redirectToRoute("login");
        }
        return $this->render('user/register.html.twig', [
            'formulaire' => $formulaire->createView()
        ]);
    }

    /**
     *
     * @Route("/login", name="login")
     *
     */
    public function login(){
        return $this->render('user/login.html.twig');
    }

    /**
     *
     * @Route("/logout", name="logout")
     *
     */
    public function logout(){

    }
}
