<?php

namespace App\Controller;
use App\Entity\User;
use App\Entity\PasswordUpdate;
use App\Form\PasswordUpdateType;
use App\Form\AccountType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Form\RegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Form\FormError as FormError;
class AccountController extends AbstractController
{
    /**
     * Permet de gerer et d'afficher le fomulaire de connexion 
     * @Route("/login", name="account_login")
     * @return  Response 
     */
    public function login(AuthenticationUtils $utils)
    { 
        //Recuperation des erreurs 
        $error=$utils->getLastAuthenticationError();
        //Obtenir le dernier utilisateur connecté 
        $username=$utils->getLastUsername();
        return $this->render('account/login.html.twig', ['hasError'=>$error==!null, 'username'=>$username

        ]);
    } 
     /**
      * Permet de se deconnecter 
      * 
      * @Route("/logout", name="account_logout")
      * 
      */

    public function logout(){


    }
    
    /**
     * Permet d'afficher le formulaire d'inscription
     * 
     * @Route("/register", name="account_register")
     * 
     */
    public function register(Request $request, ObjectManager $manager,
      UserPasswordEncoderInterface $encoder )
     {

      $user = new User();
       
      $form=$this->createForm(RegistrationType::class, $user);
      $form->handleRequest($request);
            // Est ce que le formulaire a bien été  soumis ou pas et valide ?
            if ($form->isSubmitted() && $form->isValid())
            { 
                // Encodage du mot de passe tout en lui metant dans la variable Hash
                $hash=$encoder->encodePassword($user, $user->getHash());
                // Je demade au manager de persister l'image 
                //Modification de Hash au mot passe nouvellement encoder
                  $user->setHash($hash);
                $manager->persist($user);
                //Appel a la Formule magique 
                $manager->flush();
                $this->addFlash('Success', "Votre compte a bien été créer !
                 Vous pouvez maintenant vous connecter...");
                   //redirection vers la page de connexion 
              return $this->redirectToRoute('account_login');

            }   
          
      return $this->render('account/registration.html.twig', ['form'=>$form->createview()]);
    }
     
    /**
     * Permet d'affichier et de traiter le formulaire de modification de profil
     *@Route("/account/profile", name="account_profile")
     *@IsGranted("ROLE_USER")
     *@return Response 
     */
    public function profile(Request $request, ObjectManager  $manager ){
        //Obtenir le dernier utilisateur connecter
      $user=$this->getUser();
       /*Création du formulaire en lui passant en parametre la classe AccountType 
       * et l'instance de la classe user
       */
      $form=$this->createForm(AccountType::class, $user);
      $form->handleRequest($request);
      //Conditon qui permet de savoir si le formulaire et soumise et valide 
      if($form->isSubmitted() && $form->isValid()){
        //persistance
        $manager->persist($user);
        // Appel a la formule maqique 
        $manager->flush();
        //Ajout d'un  message flash qui sera prise en compte dès que le formulaire est bien soumise  
         $this->addFlash('success', "Les données du profit ont été enregistrer avec succé !");
      }
       return $this->render('account/profile.html.twig',['form'=>$form->createview()]);
    }
     
    /**
     * Permet de modifier le mot de passe 
     *
     * @Route("/account/password_update", name="account_password")
     * @IsGranted("ROLE_USER")
     */
    public function  updatePassword(Request $request, UserPasswordEncoderInterface $encoder,
          ObjectManager $manager )
     {
       //Obtenir le dernier utilisateur connecté
        $user=$this->getUser();
        //instanciation
       $passwordUpdate = new PasswordUpdate();
       //Appel  de  la  classe (type) et on liu passe en parametre la variation passwordUpdate
       $form=$this->createForm(PasswordUpdateType::class, $passwordUpdate);
       $form->handleRequest($request);

       if($form->isSubmitted() && $form->isValid()){
         //1. Verifier que le oldPassword du formulaire soit le même que le mot passe de usr
           if(!password_verify($passwordUpdate->getOldPassword(), $user->getHash())){
                //Gerer  l'erreur (dans le cas ou le mot de passe actuel n'est pas valide)
                $form->get('oldPassword')->addError(new FormError("Le mot de passe que vous avez 
                tapé n'est pas votre mot de passe actuel !"));
           }else{
                    //Créer un nouveau mot de passe 
               $newPassword=$passwordUpdate->getNewPassword();
                  //Encodage  du mot de passe 
               $hash=$encoder->encodePassword($user, $newPassword);
                //lecture du mot de passe encoder
               $user->setHash($hash);
                 // On demade au manager de persister 
               $manager->persist($user);
                // Formule magique 
               $manager->flush();
                //Ajout d'un message flash
               $this->addFlash('success',
                    "Votre mot de passe a bien été enregistrer !"
               );
                //Redirection vers la page d'accueil 
               return $this->redirectToRoute('home_index');
           }
       }
      return $this->render('account/password.html.twig', ['form'=>$form->createview()]);

    }

     /**
      * Permet d'affaichier le proofilt de l'utlisateur connecté
      *@Route("/account", name="account_index")
      *@IsGranted("ROLE_USER")
      * @return Response
      */
     public function myAccount(){

      return $this->render('user/index.html.twig', ['user'=>$this->getUser()]);

     }
     
     /**
      * Permet d'afficher la liste des réservations faites par l'utilisateurs 
      * @Route("/account/bookings", name="account_bookings")
      * 
      * @return  Response 
      */

     public function bookings(){

      return $this->render('account/bookings.html.twig');
     }
}
