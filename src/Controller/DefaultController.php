<?php

/*
 * This file is part of the COURAT application.
 *
 * (c) Bechir Ba and contributors
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use App\Entity\Commandes;
use App\Service\Cart\CartService;
use App\Repository\UserRepository;
use App\Repository\ArticlesRepository;
use App\Repository\StockRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{

    /**
     * @Route("/", name="base_index")
     */
    public function index(CartService $cartService,ArticlesRepository $articlesRepository): Response
    {
        $panierWithData = $cartService->getFullCart();

        $total = $cartService->getTotal();

        return $this->render('default/index.html.twig', [
            'items' => $panierWithData,
            'total' => $total,
            'articles' => $articlesRepository->findAll()
        ]);
    }

    public function my_cart(CartService $cartService,ArticlesRepository $articlesRepository): Response
    {
        $panierWithData = $cartService->getFullCart();

        $total = $cartService->getTotal();

        return $this->render('default/my-cart.html.twig', [
            'items' => $panierWithData,
            'total' => $total,
            'articles' => $articlesRepository->findAll()
        ]);
    }

    public function add($id, CartService $cartService)
    {

        $cartService->add($id); 
        
        return $this->redirectToRoute('base_index');

    }

    public function remove($id, CartService $cartService){

        $cartService->remove($id); 
       
        return $this->redirectToRoute('my_cart');
    }

    public function left(CartService $cartService, $active  ){

        $panierWithData = $cartService->getFullCart();

        return $this->render("admin/common/left-sidebar.twig",[
            'items' => $panierWithData,
            'active' => $active
        ]);
    }

    public function cart(CartService $cartService){

        return $this->render("admin/common/in-cart.html.twig",[
            'items' => $cartService->getFullCart()
        ]);
    }

    public function validate(EntityManagerInterface $manager,CartService $cartService, UserRepository $repo,ArticlesRepository $medo){
        
        $user = $this->getUser();

        $panierWithData = $cartService->getFullCart();
        $commande = new Commandes();
        foreach ($panierWithData as $panier) {
            $commande->setQte($panier['quantity'])
            ->setTotal($cartService->getTotal())
            ->setCreatedAt(new \DateTime())
            // ->setUpdated($commande->getCreated())
            ->setUser($repo->find($user))
            ->setArticle($medo->find($panier['product']->getId()));
            $manager->persist($commande);

            // $stock = $repostock->findOneBy(['medoc' => $panier['product']->getId()]);
            // $init = $stock->getFinal();
            // $final = $init - $panier['quantity'];
            // $stock->setFinal($final);
            // $manager->persist($stock);
      
        }
       
        $manager->flush();

        $cartService->removeAll();

        return $this->redirectToRoute('base_index');
    }


}
