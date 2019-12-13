<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Repository\AdRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use App\Service\PaginationService;
use App\Form\AdType;

class AdminAdController extends AbstractController
{
    /**
     * @Route("/admin/ads/{page}", name="admin_ads_index", requirements={"page" : "\d+"})
     */
    public function index(AdRepository $repo, $page=1, PaginationService $pagination)
    {
          

        $pagination->setEntityClass(Ad::class)
        ->setPage($page);
        //->setRoute('admin_ads_index');

        return $this->render('admin/ad/index.html.twig', [
            'pagination' =>$pagination
        ]);
    }
 /**
  * Permet d'afficher le  formulaire d'edition pour l'administration
  * 
  * @Route("/admin/ads/{id}/edit", name="admin_ads_edit")
  * 
  *@param Ad $ad
  *
  *@return Response 
  */

  public function edit(Ad $ad, Request $request, ObjectManager $manager){

     $form=$this->createForm(AdType::class, $ad);
     $form->handleRequest($request);

     if($form->isSubmitted() && $form->isValid()){

           $manager->persist($ad);

           $manager->flush();

           $this->addFlash('success', "L'annonce <strong> {$ad->getTitle()} </strong> a bien été enregistrée !");
     }
     

      return $this->render('admin/ad/edit.html.twig', ['ad'=>$ad, 'form'=>$form->createView()]);

    }

    /**
     * permet de supprimer une annonce 
     * 
     * @param ObjectManager $manager
     * @param Ad $ad
     * 
     * @Route("/admin/ads/{id}/delete", name="admin_ads_delete")
     * 
     * @return Response 
     * 
     * 
     */

    public function delete(Ad $ad, ObjectManager $manager){

        if(count($ad->getBookings()) > 0){

            $this->addFlash('warning', " Vous ne pouvez pas supprimer l'annonce 
               <strong> {$ad->getTitle()} </strong> car elle posséde déja des réservations ! "
        );

        } else {

            $manager->persist($ad);
            $manager->flush();
    
            $this->addFlash('success', " L'annonce <strong> {$ad->getTitle()} </strong> à bien été supprimée !");
          
        }
         
        return $this->redirectToRoute('admin_ads_index');
    }
     
}
