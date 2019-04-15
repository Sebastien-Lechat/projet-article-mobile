<?php

namespace App\Controller;
use App\Entity\Ad;
use App\Entity\Image;
use App\Form\AdType;
use App\Repository\AdRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
     */
    public function index(AdRepository $repository)
    {
           //Creation de repository 
          //$repository=$this->getDoctrine()->getRepository(Ad::class);
          //Je vais demandé au repository d'aller chercher toute les annones dans la base de donnée 
           $ads=$repository->findAll();
        return $this->render('ad/index.html.twig', ['ads'=>$ads
        ]);
    }

    /**
     * Permet de créer une annonce  
     *@Route("/ads/new", name="ads_create")
     *@IsGranted("ROLE_USER")
     *@return Response 
     */ 
     public function create(Request $request, ObjectManager $manager){
        
          //Création de l'instance de l'entité Ad
          $ad= new Ad();
          
          //Création de formulaire
         $form=$this->createForm(AdType::class, $ad);
         //permet de parcourir la requête et d'extraire les informations du formulaire
           $form->handleRequest($request);
           // Est ce que le formulaire a bien été  soumis ou pas et valide ?
            if ($form->isSubmitted() && $form->isValid()){

                 //Boucle l'image c'est a dire  vouloir passé sur chaque image 
                 foreach ($ad->getImages() as $image) {
                   // J'ai precié a l'image quelle a appartient a l'annonce 
                   $image->setAd($ad);
                   // Je demade au manager de persister l'image 
                   $manager->persist($image);
                 }
                  //Recuperation de l'auteur de l'annonce 
                  $ad->setAuthor($this->getUser());
                  

                //Appel au manager de doctrine pour en registrement dans la BD
                 //$manager=$this->getDoctrine()->getManager();
                //On demande a notre manager de persister
                $manager->persist($ad);
                //Envoie dse données dans la  BD 
                $manager->flush();
                  //Ajout d'une Flash
                 $this->addFlash(

                  'success', "l'annonce <strong> {$ad->getTitle()} </strong> a bien été enregistrer!"
                );
                //Redirection vers une nouvelle annonce 
                return $this->redirectToRoute('ads_show', ['slug'=>$ad->getSlug()]);
                 
             }
         //Création de la vue 
      return $this->render('ad/new.html.twig', ['form'=>$form->createView()]);
          
     }
      /**
     * Permet de créer le formulaisre d'edition 
     * 
     * @Route("/ads/{slug}/edit", name="ads_edit")
     * @Security("is_granted('ROLE_USER') and user === ad.getAuthor()", message="Cette annonce ne vous appartient pas,
     * vous ne pouvez pas la modifier")
     * @return Response
     */

    public function edit(Ad $ad , Request $request, ObjectManager $manager){
     
          //Création de formulaire
          $form=$this->createForm(AdType::class, $ad);
          //permet de parcourir la requête et d'extraire les informations du formulaire
            $form->handleRequest($request);
            // Est ce que le formulaire a bien été  soumis ou pas et valide ?
            if ($form->isSubmitted() && $form->isValid()){

              //Boucle l'image c'est a dire  vouloir passé sur chaque image 
              foreach ($ad->getImages() as $image) {
                // J'ai precié a l'image quelle a appartient a l'annonce 
                $image->setAd($ad);
                // Je demade au manager de persister l'image 
                $manager->persist($image);
              }

             //Appel au manager de doctrine pour en registrement dans la BD
              //$manager=$this->getDoctrine()->getManager();
             //Persistance de la nouvvelle annonce 
             $manager->persist($ad);
             //Envoie de la requête dans la base de donnée
             $manager->flush();
               //Ajout d'une Flash
              $this->addFlash(

               'success', " Les modifications de l'annonce <strong> {$ad->getTitle()} </strong> ont bien été enregistrées!"
             );
             //Redirection vers une nouvelle annonce 
             return $this->redirectToRoute('ads_show', ['slug'=>$ad->getSlug()]);
              
          }
             
      return $this->render('ad/edit.html.twig', ['form'=>$form->createview(), 'ad'=>$ad]);
    }

     /**
      * Permet d'affichier une annonce
      * 
      *@Route("/ads/{slug}", name="ads_show")
      *@return Response 
      */
        // Avec le paramconverter
    public function show(Ad $ad){
      // Je recupere l'annonce qui correspond au slug
        //$ad=$repository->findOneBySlug($slug);
        return $this->render('ad/show.html.twig', ['ad'=>$ad
        ]);
    
    }
    /**
     *Permet de supprimer une annonce 
     *@Route("/ad/{slug}/delete", name="ads_delete")
     *@param Ad $ad 
     *@param  ObjectManager $manager
     *@Security("is_granted('ROLE_USER') and user === ad.getAuthor()", message="Vous n'avez pas le droit
     *  d'accéder a ce ressource ! "
     * )
     */
    
    public function delete(Ad $ad, ObjectManager $manager){
        $manager->remove($ad);
        $manager->flush();

        $this->addFlash(
          'success',
          "L'annonce <strong> {$ad->getTitle()}  </strong> a bien été supprimer !"
   
         );
      return $this->redirectToRoute("ads_index");
         
    }
    
}
