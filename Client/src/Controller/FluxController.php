<?php

namespace App\Controller;

use App\Entity\Flux;
use App\Form\FluxType;
use App\Repository\FluxRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/flux")
 */
class FluxController extends AbstractController
{
    /**
     * @Route("/", name="flux_index", methods={"GET"})
     */
    public function index(FluxRepository $fluxRepository): Response
    {
        return $this->render('flux/index.html.twig', [
            'fluxes' => $fluxRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="flux_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $flux = new Flux();
        $form = $this->createForm(FluxType::class, $flux);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($flux);
            $entityManager->flush();
             //Redirection vers la liste des flux 
            return $this->redirectToRoute('flux_index');
        }

        return $this->render('flux/new.html.twig', [
            'flux' => $flux,
            'form' => $form->createView(),
        ]);
    }

    /** 
     * Permet d'afficher un flux RSS
     * @Route("/{id}", name="flux_show", methods={"GET"})
     */
    public function show(Flux $flux): Response
    {
        return $this->render('flux/show.html.twig', [
            'flux' => $flux,
        ]);
    }

    /**
     * Permet d'éditer un flux
     * @Route("/{id}/edit", name="flux_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Flux $flux): Response
    {
        $form = $this->createForm(FluxType::class, $flux);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('flux_index');
        }

        return $this->render('flux/edit.html.twig', [
            'flux' => $flux,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Permet de supprimer un flux 
     * @Route("/{id}", name="flux_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Flux $flux): Response
    {
        if ($this->isCsrfTokenValid('delete'.$flux->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($flux);
            $entityManager->flush();
        }

        return $this->redirectToRoute('flux_index');
    }
}
