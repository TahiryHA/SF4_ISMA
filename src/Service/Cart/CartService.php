<?php

namespace App\Service\Cart;

use App\Repository\ArticlesRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService {

    protected $session;
    protected $articlesRepository;

    public function __construct(SessionInterface $session, ArticlesRepository $articlesRepository)
    {
        $this->session = $session;
        $this->articlesRepository = $articlesRepository;
    }

    public function add(int $id){
       
        $panier = $this->session->get('panier',[]);
        if (!empty($panier[$id])) { 
            $panier[$id]++;
        }else{
            $panier[$id] = 1;
        }
        $this->session->set('panier',$panier);

    }
    public function remove(int $id){
        $panier = $this->session->get('panier',[]);
        if (!empty($panier[$id])) {
            unset($panier[$id]);
        }
        $this->session->set('panier',$panier);
    }

    public function removeAll(){
       $panier = $this->session->get('panier',[]);
       
       foreach ($this->getFullCart() as $item) {
          unset($panier[$item['product']->getId()]);
       }
       $this->session->set('panier',$panier);
        
    }

    public function getFullCart(): array
    {
        $panier = $this->session->get('panier',[]);
        $panierWithData = [];
        foreach ($panier as $id => $quantity) {
            $panierWithData[] = [
                'product' =>$this->articlesRepository->find($id),
                'quantity' => $quantity
            ];
        } 

        return $panierWithData;
    } 

    public function getTotal() : float
    {
        $total = 0;
          
        foreach ($this->getFullCart() as $item) {
            $total += $item['product']->getPrice() * $item['quantity'];
        }

        return $total;
    }
}