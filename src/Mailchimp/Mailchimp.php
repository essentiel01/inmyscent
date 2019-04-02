<?php

// cette classe défini toutes les methodes permettant d'interagir avec l'api de Mailchimp.

namespace App\Mailchimp;


class Mailchimp 
{
    public $endpoint = "https://us20.api.mailchimp.com/3.0";

    public $credentials;

    public $certificatPath;

    /**
     * défini les données de connexion à l'api et le chemin vers le certificat
     *
     * @param String $username
     * @param String $apiKey
     */
    public function __construct(String $username, String $apiKey)
    {
        $this->credentials = "$username:$apiKey";

        $this->certificatPath = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'certificat' . DIRECTORY_SEPARATOR . 'DigiCertGlobalRootCA.crt';
    }

    /**
     * Ajoute un nouvel abonné à la liste dont l'id est spécifié
     *
     * @param String l'id de la liste
     * @param String les données de l'abonné
     * @return Array
     */
    public function addSubscriber(String $listId, String $subscriberData): Array
    {
        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL => "$this->endpoint/lists/$listId/members/",
            CURLOPT_POST => true,
            CURLOPT_HEADER => 'content-type: application/json',
            CURLOPT_POSTFIELDS => $subscriberData,
            CURLOPT_USERPWD => $this->credentials,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 1,
            CURLOPT_CAINFO => $this->certificatPath)
        );

        $result = curl_exec($ch);
            
        if ($result) 
        {
            if (curl_getInfo($ch, CURLINFO_RESPONSE_CODE) === 404) 
            {
                return array('status' => "fail",
                'message' => "Resource Not Found");
            }

            return array('status' => "success",
                        'message' => "Votre inscription a été bien prise en compte");
        } else 
        {
            return array('status' => "fail",
                        'message' => curl_error($ch) );
        }

        curl_close($ch);

    }
    
    /**
     * Vérifie si l'utilisateur est déjà abonné
     *
     * @param String l'id de la liste
     * @param String l'email de l'utilisateur
     */
    public function subscriberExist(String $listId, String $email)
    {
        $emailId = md5($email);

        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL => "$this->endpoint/lists/$listId/members/$emailId",
            CURLOPT_HEADER => 'content-type: application/json',
            CURLOPT_USERPWD => $this->credentials,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 1,
            CURLOPT_CAINFO => $this->certificatPath)
        );

        $subscriber = curl_exec($ch);

        if ($subscriber) 
        {
            if (curl_getInfo($ch, CURLINFO_RESPONSE_CODE ) === 404) {
                return false;
            }
            return true;
        } 
        else 
        {
            return false;
        }
    }
}
