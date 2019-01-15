<?php

namespace App\Controller;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Brand;

use Symfony\Component\Cache\Simple\FilesystemCache;


class HomeController extends AbstractController
{
    private $cache;

    public function __construct() {
        $this->cache = new FilesystemCache('homePage');
    }
   
    /**
     * Affiche la home page
     *
     * @return void
     */
    public function index()
    {
        return $this->render('home/index.html.twig', [
                    'title' => 'InMyScent',
                    ]);
    }

    /**
     * retourne la liste de toutes les marques au format json
     *
     * @return void
     */
    public function brands() {
        if ($this->cache->has('brands')) {
            return new JsonResponse($brands, 200, [], true);
        } else {

            try {
                $brands = $this->getDoctrine()->getRepository(Brand::class)->findAll();
            
                if ($brands) {
                    // converti au format json
                    $brands = $this->serializer('brand')->serialize($brands, 'json');
                    // met réusltat en cache
                    $this->cache->set('brnads', $brands, 3600);

                    return new JsonResponse($brands, 200, [], true);
                }   
                else {
                    $error = $this->serializer()->serialize(['success' => false,
                        'message' => 'empty data found'], 'json');
                    return new JsonResponse($error, 200, [], true);
                }     
            }
            catch(\Exception $e) {
                $error = $this->serializer()->serialize(['success' => false,
                    'message' => 'fall to load autocomplete data'], 'json');
                return new JsonResponse($error, 200, [], true);
            }
        }
    }

    public function products($id, $name) {

    }

    /**
     * objet serializer
     *
     * @param string $entityName le nom de l'entity concerné par la requête
     * @return void
     */
    private function serializer(String $entityName = '') {
       
        $encoders = array(new JsonEncoder());
        
        if  ($entityName != '') {
            $normalizers = array((new ObjectNormalizer())->setIgnoredAttributes([$entityName]));
        } else {
            $normalizers = array(new ObjectNormalizer());
        }
            
        $serializer = new Serializer($normalizers, $encoders);

        return $serializer;
    }

}
