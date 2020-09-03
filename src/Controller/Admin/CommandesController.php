<?php

namespace App\Controller\Admin;

use App\Entity\Commandes;
use App\Form\CommandesType;
use App\Repository\CommandesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/commandes")
 */
class CommandesController extends AbstractController
{
    /**
     * @Route("/", name="commandes_index")
     */
    public function index(SessionInterface $session,CommandesRepository $commandeRepository): Response
    {
        $panier = $session->get('panier',[]);
        $panierWithData = [];
        foreach ($panier as $id => $quantity) {
            $panierWithData[] = [
                'product' => $commandeRepository->find($id),
                'quantity' => $quantity
            ];
        } 

        return $this->render('admin/commandes/index.html.twig', [
            'commandes' => $commandeRepository->findAll(),
            'items' => $panierWithData
        ]);
    }

    /**
     * @Route("/new", name="commandes_new")
     */
    public function new(Request $request): Response
    {
        $commande= new Commandes();
        $form = $this->createForm(CommandesType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $commande->setCreatedAt(new \DateTime());
            // $commande->setUpdated(new \DateTime());
            
            $entityManager->persist($commande);
            $entityManager->flush();

            return $this->redirectToRoute('commandes_index');
        }

        return $this->render('admin/commandes/new.html.twig', [
            'Commandes' => $commande,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="commandes_show")
     */
    public function show(Commandes $commande): Response
    {

        return $this->render('admin/commandes/show.html.twig', [
            'commande' => $commande,

        ]);
    }

    /**
     * @Route("/{id}/edit", name="commandes_edit")
     */
    public function edit(Request $request, Commandes $commande): Response
    {
        $form = $this->createForm(CommandesType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $commande->setUpdated(new \DateTime());

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('commandes_index');
        }

        return $this->render('admin/commandes/edit.html.twig', [
            'Commandes' => $commande,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="commandes_delete")
     */
    public function delete(Request $request, Commandes $commande): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commande->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($commande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('commandes_index');
    }

}
