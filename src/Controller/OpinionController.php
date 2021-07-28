<?php

namespace App\Controller;

use App\Entity\Opinion;
use App\Repository\OpinionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OpinionController extends AbstractController
{
    /**
     * @Route("/opinion/delete/{id}", name="delete_opinion")
     */
    public function delete(Opinion $opinion, EntityManagerInterface $manager): Response
    {
        $manager->remove($opinion);
        $manager->flush();
        return $this->redirectToRoute("movie");
    }


}
