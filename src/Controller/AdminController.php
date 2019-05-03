<?php

namespace App\Controller;



use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class AdminController extends AbstractController
{
   
    public function index(): Response
    {
        $title = 'Bienvenue sur la page d\'administration du site';

        $warning = 'L\'accès à cette page est strictement réservée aux personnes ayant un role administrateur. Si vous êtes arrivé sur cette page par erreur merci d\'en informer l\administrateur du site';

        $admin = 'admin@inmyfragrance.com';

        return $this->render('admin/index.html.twig', [
            'title' => $title,
            'warning' => $warning,
            'admin' => $admin
            ]);
    }
}