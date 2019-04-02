<?php

// cette classe définie les méthods permettant de gérer les abonnements et les désabonnenmts à la newsletter.

namespace App\Controller;



use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Form\SubscritionFormType;

use App\Mailchimp\Mailchimp;


class NewsletterController extends AbstractController
{
    protected $Mailchimp;

    public function __construct()
    {
        $this->Mailchimp = new Mailchimp('InMyFragrance', '9db76c4c9f4418492511d39506e518a5-us20');
    }

    /**
     * inscription à la newsetter via l'api de mailchimp. on vérifie si l'utiisateur est djà abonné sinon on l'abonne. si oui on affiche le message de succès d'enregistrement sans l'enregistrer à nouveau
     *
     * @param Request $request
     * @return Response
     */
    public function subscribe(Request $request): Response
    {
        $form = $this->createForm(SubscritionFormType::class);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form['email']->getData();
            $sexe = $form['sexe']->getData();
           
            $listId = 'f3b3f9f8d4';
            $subscriberData = json_encode(
                array(
                    'email_address' => $email, 
                    'status'=> 'subscribed', 
                    'tags'=> [ $sexe ]     )
            );
           
            $subscriberCheck = $this->Mailchimp->subscriberExist($listId, $email);
            if ($subscriberCheck) 
            {
               $title = 'Votre inscriptio a été bien prise en compte';
            }
            else 
            {
                $response = $this->Mailchimp->addSubscriber($listId, $subscriberData);
                if ($response['status'] == "success") 
                {
                    $title = $response['message'];
                }
                elseif ($response['status'] == "fail") 
                {
                    $title = $response['message'];
                }
            }
        }


        return $this->render('newsletter/subscrition.html.twig', [
            'title' => $title
            ]);
    }
}