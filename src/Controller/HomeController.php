<?php

namespace App\Controller;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

use Symfony\Component\HttpFoundation\Request;
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
                    'title' => 'InMyScent'
                    ]);
    }

    /**
     * retourne la liste de toutes les marques au format json
     *
     * @return JsonResponse la liste de toutes les marque
     */
    public function brands() {

        if ($this->cache->has('brands')) 
        {
            return new JsonResponse($this->cache->get('brands'), 200, [], true);
        } 
        else 
        {

            try {
                $brands = $this->getDoctrine()->getRepository(Brand::class)->findAll();
            
                if ($brands) 
                {
                    // converti au format json
                    $brands = $this->serializer('brand')->serialize($brands, 'json');
                    // met réusltat en cache
                    $this->cache->set('brands', $brands, 3600);

                    return new JsonResponse($brands, 200, [], true);
                }   
            } 
            catch(\Exception $e) 
            {
                $error = $this->serializer()->serialize(['success' => false,
                    'message' => 'fall to load autocomplete data'], 'json');
                return new JsonResponse($error, 200, [], true);
            }
        }
    }

    /**
     * retourne les produits de la marque spécifiée
     *
     * @param Request $request objet request
     * @return JsonResponse liste des produits ou un message d'erreur au format json
     */
    public function products(Request $request) {
        
        $brandName = strip_tags($request->request->get('brandName'));

        if($this->cache->has($brandName)) 
        {
            return new JsonResponse($this->cache->get($brandName), 200, [], true);
        } 
        else 
        {
            try 
            {
                $products = $this->getDoctrine()->getRepository(Product::class)->findByBrandName($brandName);
            
                if ($products) 
                {
                    // converti au format json
                    $products = $this->serializer('brand')->serialize($products, 'json');
                    // met réusltat en cache
                    $this->cache->set($brandName, $products, 3600);
                    dump($products);
                    return new JsonResponse($products, 200, [], true);
                } 
                else 
                {
                    $error = $this->serializer()->serialize(['success' => false,
                    'type' => 'not found',
                    'message' => 'no products found'], 'json');
                    return new JsonResponse($error, 200, [], true);
                }
            } 
            catch (\Exception $e) 
            {
                $error = $this->serializer()->serialize(['success' => false,
                    'type' => 'fail',
                    'message' => 'fall to load autocomplete data'], 'json');
                return new JsonResponse($error, 200, [], true);
            }
        }
    }


    public function search(Request $request) {
        $brandName  = $this->_isValid( $request->request->get('brand') );
        $productName  = $this->_isValid( $request->request->get('product') );
       $param = $this->serializer()->serialize(['brand' => $brandName,
                    'product' => $productName
                    ], 'json');
                return new JsonResponse($param, 200, [], true);
    }


    /**
     * nettoie les données provenant du formulaire de recherche
     *
     * @param Mixed $data
     * @return void
     */
    private function _isValid($data) {
        if (is_array($data) || is_object($data))
        {
            foreach ($data as &$v)
            {
                strip_tags($v);
                stripslashes($v);
                trim($v);
            }

            unset($v);
            return $data;
        } 
        else 
        {
            $data = strip_tags($data);
            $data = stripslashes($data);
            $data = trim($data); 

            return $data;
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
        
        if  ($entityName != '') 
        {
            $normalizers = array((new ObjectNormalizer())->setIgnoredAttributes([$entityName]));
        } 
        else 
        {
            $normalizers = array(new ObjectNormalizer());
        }
            
        $serializer = new Serializer($normalizers, $encoders);

        return $serializer;
    }

}
