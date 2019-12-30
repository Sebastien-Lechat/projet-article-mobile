<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/article")
 */
class ArticleController extends AbstractController
{
    /**
     * Affichage de tout les  articles 
     * @Route("/", name="article_index", methods={"GET"})
     */
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('article/index.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }

    /**
     * Permet de créer un nouveau article
     * @Route("/new", name="article_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $article = new Article();
        //Création d'un formulaire
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
            //Est ce que le formulaire a bien  été soumise et valide?
        if ($form->isSubmitted() && $form->isValid()) {
            //Appel a l'entityManager
            $entityManager = $this->getDoctrine()->getManager();
            //On persist
            $entityManager->persist($article);
            //Formule magique
            $entityManager->flush();
             
            //Redirection vers la page des articles 
            return $this->redirectToRoute('article_index');
        }

        return $this->render('article/new.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Permet d'afficher un article
     * @Route("/{id}", name="article_show", methods={"GET"})
     */
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * Permet d'editer un article 
     * @Route("/{id}/edit", name="article_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Article $article): Response
    {   
        //Création  du formulaire
        $form = $this->createForm(ArticleType::class, $article);
        //Permet de parcourir la requête et d'extraire les informations du formulaire
        $form->handleRequest($request);
         // Est-ce que le formulaire a bien été  soumis ou pas et valide ?
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
             // Redirection
            return $this->redirectToRoute('article_index');
        }

        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Permet de supprimer un article
     * @Route("/{id}", name="article_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Article $article): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($article);
            $entityManager->flush();

            $this->addFlash(
                'success',
                "L'article <strong> {$article->getTitle()}  </strong> a bien été supprimer !"
         
               );
        }

        return $this->redirectToRoute('article_index');
    }
}
