<?php

namespace App\Controller;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Brand;
use App\Entity\Product;

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
        // $this->cache->clear();
        return $this->render('home/index.html.twig', [
                    'title' => 'InMyScent',
                    ]);
    }

    /**
     * retourne la liste de toutes les marques au format json
     *
     * @return JsonResponse la liste de toutes les marque
     */
    public function brands() {

        if ($this->cache->has('brands')) {
            return new JsonResponse($this->cache->get('brands'), 200, [], true);
        } else {

            try {
                $brands = $this->getDoctrine()->getRepository(Brand::class)->findAll();
            
                if ($brands) {
                    // converti au format json
                    $brands = $this->serializer('brand')->serialize($brands, 'json');
                    // met réusltat en cache
                    $this->cache->set('brands', $brands, 3600);

                    return new JsonResponse($brands, 200, [], true);
                }   
            } catch(\Exception $e) {
                $error = $this->serializer()->serialize(['success' => false,
                    'message' => 'fall to load autocomplete data'], 'json');
                return new JsonResponse($error, 200, [], true);
            }
        }
    }

    /**
     * retourne les produits de la marque spécifiée
     *
     * @param Int $id id de la marque
     * @param String $slug slug de la marque
     * @return JsonResponse liste des produits ou un message d'erreur au format json
     */
    public function products($id, $slug) {

        if($this->cache->has($slug)) {
            return new JsonResponse($this->cache->get($slug), 200, [], true);
         } else {
            try {
                $products = $this->getDoctrine()->getRepository(Product::class)->findByBrandId($id);
            
                if ($products) {
                    // converti au format json
                    $products = $this->serializer('brand')->serialize($products, 'json');
                    // met réusltat en cache
                    $this->cache->set($slug, $products, 3600);
                    dump($products);
                    return new JsonResponse($products, 200, [], true);
                } 
            } catch (\Exception $e) {
                $error = $this->serializer()->serialize(['success' => false,
                    'message' => 'fall to load autocomplete data'], 'json');
                return new JsonResponse($error, 200, [], true);
            }
        }
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
