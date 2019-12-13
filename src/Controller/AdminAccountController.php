<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminAccountController extends AbstractController
{
    /**
     * Contrôleur pour l'administration
     * @Route("/admin/login", name="admin_account_login")
     * 
     * 
     */
    public function login(AuthenticationUtils $utils)
    {
        //Recuperation des erreurs 
        $error=$utils->getLastAuthenticationError();
        //Obtenir le nom de  dernier utilisateur connecté 
        $username=$utils->getLastUsername();

        return $this->render('admin/account/login.html.twig', ['hasError'=>$error==!null, 'username'=>$username

        ]);
    }
    /**
     * Permet de ce deconnecter 
     * @Route("/admin/logout", name="admin_account_logout")
     * 
     *@return void 
     * 
     */

    public function logout(){


    }
}
