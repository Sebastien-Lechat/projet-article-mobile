<?php

namespace App\Controller;

use App\Entity\Booking;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\BookingRepository;
use App\Service\PaginationService;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use App\Form\AdminBookingType;


class AdminBookingController extends AbstractController
{
    /**
     * @Route("/admin/bookings/{page}", name="admin_booking_index", requirements={"page" : "\d+"})
     */
    public function index(BookingRepository $repo, $page=1, PaginationService $pagination)
    {
        
        $pagination->setEntityClass(Booking::class)
                   ->setPage($page);
        

        return $this->render('admin/booking/index.html.twig', [
            'pagination' =>$pagination
            
      
        ]);
    }

    /**
  * Permet d'afficher le  formulaire d'edition pour l'administration
  * 
  * @Route("/admin/bookings/{id}/edit", name="admin_bookings_edit")
  * 
  *@param  Booking $booking
  *
  *@return Response 
  */

  public function edit(Booking $booking, Request $request, ObjectManager $manager){
      
    
     $form=$this->createForm(AdminBookingType::class, $booking);
     $form->handleRequest($request);

     if($form->isSubmitted() && $form->isValid()){
       //Modification  //calcul du prix et la durée de réservation
      $booking->setAmount($booking->getAd()->getPrice() * $booking->getDuration());
      $manager->persist($booking);

     $manager->flush();

        $this->addFlash('success', "Réservation <strong> {$booking->getId()} </strong> a bien été enregistrée !");
   }
    

     return $this->render('admin/booking/edit.html.twig', ['booking'=>$booking, 'form'=>$form->createView()]);

   }

   /**
     * permet de supprimer une réservation
     * 
     * @param ObjectManager $manager
     * @param Booking $booking
     * 
     * @Route("/admin/bookings/{id}/delete", name="admin_bookings_delete")
     * 
     * @return Response 
     * 
     * 
     */

    public function delete(Booking $booking, ObjectManager $manager){

    
      $manager->remove($booking);
      $manager->flush();

    $this->addFlash('success', "La réservation a bien été supprimé !");

      return $this->redirectToRoute('admin_booking_index');
}

}



