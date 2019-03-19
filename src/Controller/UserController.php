<?php

namespace App\Controller;



use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Repository\UserRepository;


class UserController extends AbstractController
{
   
    public function index(UserRepository $userRepository): Response
    {
        $title = 'Gestion des utilisateurs';
        $subTitle = 'Liste des utilsateurs';
        $users = $userRepository->findAll();   
             
        return $this->render('user/index.html.twig', [
            'title' => $title,
            'subTitle' => $subTitle,
            'users' => $users
            ]);
    }
}