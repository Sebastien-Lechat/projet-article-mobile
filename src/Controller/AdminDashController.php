<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\ObjectManager;
use App\Service\StatsService;

class AdminDashController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dash")
     */
    public function index(ObjectManager $manager, StatsService $statsService)
    {


        $stats= $statsService->getStats();

        //permet d'afficher les annonces les plus populaires 

        $bestAds=$statsService->getBestAds();

        //Permet les annonvces les moins populaires 
        $badAds=$statsService->getBadAds();
        
        //Passage des resultats a la vue(notre template twisg)
        return $this->render('admin/dash/index.html.twig', [
            'stats' => $stats,
            'bestAds'=>$bestAds,
            'badAds'=>$badAds
        ]);
    }
}
