<?php

namespace App\Controller;

Use App\Entity\Comment;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CommentRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use App\Form\AdminCommentType;
use App\Service\PaginationService;


class AdminCommentController extends AbstractController
{
    /**
     * @Route("/admin/comments/{page}", name="admin_comment_index", requirements={"page" : "\d+"})
     */
    public function index(CommentRepository $repo, $page=1, PaginationService $pagination )
    {
          
        
        $pagination->setEntityClass(Comment::class)
                   ->setPage($page);
                   

        return $this->render('admin/comment/index.html.twig', [
            'pagination' =>$pagination
        ]);
    }


    /**
     * Permet d'editer un commentaire 
     * 
     * @Route("admin/comments/{id}/edit", name="admin_comments_edit")
     * 
     */

    public function edit( Comment $comment, Request $request, ObjectManager $manager   ){
     

         $form=$this->createForm(AdminCommentType::class, $comment);
         
         $form->handleRequest($request);

         if($form->isSubmitted()  && $form->isValid()){

            $manager->persist($comment);
             
            $manager->flush();

            $this->addFlash('success', "Le commentaire <strong> n° {$comment->getId()} a bien été modifié !");
         }
        
        return $this->render('admin/comment/edit.html.twig', [
            'comment' => $comment, "form"=>$form->createView()
        ]);
   
    }


    /**
     * permet de supprimer une annonce 
     * 
     * @param ObjectManager $manager
     * @param Ad $ad
     * 
     * @Route("/admin/comments/{id}/delete", name="admin_comments_delete")
     * 
     * @return Response 
     * 
     * 
     */

    public function delete(Comment $comment, ObjectManager $manager){

    
            $manager->remove($comment);
            $manager->flush();
    
          $this->addFlash('success', "Le commentaire de <strong>  {$comment->getAuthor()->getFullName()} a bien été supprimé !");

            return $this->redirectToRoute('admin_comment_index');
    }
     
}

