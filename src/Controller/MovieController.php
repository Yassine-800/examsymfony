<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\Opinion;
use App\Form\MovieType;
use App\Form\OpinionType;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class MovieController extends AbstractController
{
    /**
     * @Route("/movie", name="movie")
     */
    public function index(MovieRepository $repository): Response
    {
        $movies = $repository->findAll();
        return $this->render('movie/index.html.twig', [
            'controller_name' => 'Liste des films',
            'movies' => $movies
        ]);
    }

    /**
     * @Route("/movie/show/{id}", name="show_movie")
     * @Route("/opinion/edit/{id}", name="edit_opinion", requirements={"id"="\d+"})
     */
    public function show(Movie $movie, Request $request, EntityManagerInterface $manager, Opinion $opinion = null)
    {
        $modeCreation = false;
        if (!$opinion) {
            $opinion = new Opinion();
            $modeCreation = true;
        }

        $formulaire = $this->createForm(OpinionType::class, $opinion);

        $formulaire->handleRequest($request);
        if ($formulaire->isSubmitted() && $formulaire->isValid()) {

            $opinion->setCreatedAt(new \DateTime());
            $opinion->setMovie($movie);
            $manager->persist($opinion);
            $manager->flush();

        }
        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
            'formulaire' => $formulaire->createView(),
            'modeCreation' => $modeCreation

        ]);
    }

    /**
     *
     * @Route("/movie/new", name="create_movie")
     * @Route("/movie/edit/{id}", name="edit_movie", requirements={"id"="\d+"})
     */
    public function create(Request $request, EntityManagerInterface $manager, Movie $movie = null, UserInterface $user)
    {


        $modeCreation = false;
        if (!$movie) {
            $movie = new Movie();
            $modeCreation = true;
        }

        if($user != $movie->getUser() && !$modeCreation){
            return $this->redirectToRoute('movie');
        }

        $formulaire = $this->createForm(MovieType::class, $movie);

        $formulaire->handleRequest($request);
        if ($formulaire->isSubmitted() && $formulaire->isValid()) {
            $movie->getUser($user);
            $movie->setYearOfRelease(new \DateTime());
            $manager->persist($movie);
            $manager->flush();

            return $this->redirectToRoute("show_movie", [
                "id" => $movie->getId()
            ]);
        }

        return $this->render('movie/create.html.twig', [
            'formulaire' => $formulaire->createView(),
            'creation' => $modeCreation
        ]);
    }

    /**
     *
     * @Route("/movie/delete/{id}", name="delete_movie")
     *
     */
    public function delete(Movie $movie, EntityManagerInterface $manager)
    {

        $manager->remove($movie);
        $manager->flush();

        return $this->redirectToRoute("movie");
    }


}
