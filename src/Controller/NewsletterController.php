<?php

namespace App\Controller;



use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Form\SubscritionFormType;

class NewsletterController extends AbstractController
{
   
    public function subscribe(Request $request): Response
    {
        $form = $this->createForm(SubscritionFormType::class);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form['email']->getData();
            $sexe = $form['sexe']->getData();
        }
        
        $title = 'Votre inscription a bien été pris en compte';


        return $this->render('newsletter/subscrition.html.twig', [
            'title' => $title
            ]);
    }
}