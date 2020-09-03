<?php

/*
 * This file is part of the COURAT application.
 *
 * (c) Bechir Ba and contributors
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller\Admin;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class HomeController extends AbstractController
{
    /**
     *@Route("/",name="admin_index") 
     */
    public function index(EntityManagerInterface $em)
    {
        return $this->render('admin/home/index.html.twig', [
            'data' => $this->getStats($em)
        ]);
    }

    public function getStats(EntityManagerInterface $em)
    {

        $now = date_format(new \DateTime('now'),'Y-m-d');

        $articles = $em->createQueryBuilder('a')->select('count(a.id)')
            ->from('App:Articles', 'a')
            ->getQuery()->getSingleScalarResult();
        
        $categories = $em->createQueryBuilder('c')->select('count(c.id)')
            ->from('App:Categories', 'c')
            ->getQuery()->getSingleScalarResult();
        
        $commandes = $em->createQueryBuilder('c')->select('count(c.id)')
            ->from('App:Commandes', 'c')
            ->getQuery()->getSingleScalarResult();

        $users = $em->createQueryBuilder('c')->select('count(c.id)')
            ->from('App:User', 'c')
            ->where('c.roles LIKE :role')
            ->setParameter('role','%ROLE_USER%')
            ->getQuery()->getSingleScalarResult();

        return [
            'articles' => $articles,
            'commandes' => $commandes,
            'categories' => $categories,
            'users' => $users

        ];
    }
}
