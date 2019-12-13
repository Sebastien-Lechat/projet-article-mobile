<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AdRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home_index")
     */
    public function index(AdRepository $adRepo, UserRepository $userRepo)
    {
        return $this->render('home.html.twig', [
            'ads' =>$adRepo->findBestAds(3),
            'users'=>$userRepo->findBestUsers(2)

        ]);
    }
}
