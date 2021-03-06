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
use App\Entity\NotFound;
use Cocur\Slugify\Slugify;

use App\Form\SubscritionFormType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

// use Symfony\Component\Cache\Simple\FilesystemCache;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class HomeController extends AbstractController
{

    protected $_cache;
    protected $_slugifier;

    public function __construct() {
        // $this->_cache = new FilesystemCache();
        $this->_cache = new FilesystemAdapter();
        $this->_slugifier = new Slugify();
    }

    /**
     * Affiche la home page
     *
     * @return void
     */
    public function index()
    {
        // $this->_cache->clear();

        // formulaire d'inscription à la newsletter: à envoyer sur la page d'accueil
        $form = $this->createForm(SubscritionFormType::class);
           
        return $this->render('home/index.html.twig', [
                    'title' => 'InMyFragrance',
                    'form' => $form->createView(),

                    ]);
    }

    /**
     * retourne la liste de toutes les marques au format json. si au moins un résultat est trouvé il est mit dans le cache de façon permmanente avec la clée 'brandList'. ce cache est invalidé chaque fois qu'il y a un ajout, une mise à jour ou une suppression dans la table brand.
     *
     * @return JsonResponse la liste de toutes les marque
     */
    public function brands() {
        if ($this->_cache->hasItem('brandList'))
        {
            $brands = $this->_cache->getItem('brandList')->get();

            //  sérialize en json
            $response = $this->_serializer('brand')->serialize(["success" => true,
            "haveContent" => true,
            "content" => $brands], 'json');

            return new JsonResponse($response, 200, [], true);
        }
        else
        {

            try {
                $brands = $this->getDoctrine()->getRepository(Brand::class)->findAll();

                if ($brands)
                {
                    // converti au format json
                    $response = $this->_serializer('brand')->serialize(["success" => true,
                    "haveContent" => true,
                    "content" => $brands], 'json');

                    // met réusltat en cache
                    $brandList = $this->_cache->getItem("brandList");
                    $brandList->set($brands);
                    $this->_cache->save($brandList);

                    return new JsonResponse($response, 200, [], true);
                } else 
                {
                    $response = $this->_serializer('brand')->serialize(["success" => true,
                    "haveContent" => false
                    ], 'json');

                    return new JsonResponse($response, 200, [], true);
                }
            }
            catch(\Exception $e)
            {
                $response = $this->_serializer()->serialize(['success' => false,
                "haveContent" => false,
                "error" => $e,
                'message' => 'fall to load autocomplete data'], 'json');
                return new JsonResponse($response, 200, [], true);
            }
        }
    }

    /**
     * retourne les produits de la marque spécifiée par le paramètre $brandName. si au moins un résultat est trouvé il est mit dans le cache de façon permmanente avec la clée '$brandName_productList'. ce cache est invalidé chaque fois qu'il y a un ajout, une mise à jour ou une suppression dans la table product.
     *
     * @param Request $request objet request
     * @return JsonResponse liste des produits ou un message d'erreur au format json
     */
    public function products(Request $request) {

        $brandName = $this->_isValid($request->request->get('brandName'));

        $brandNameSlug = $this->_slugifier->slugify($brandName);

        //$this->_cache->deleteItem($brandName.'_productList');
        if($this->_cache->hasItem($brandNameSlug.'_productList'))
        {
            $products = $this->_cache->getItem($brandNameSlug.'_productList')->get();

             // converti au format json
             $response = $this->_serializer('brand')->serialize(["success" => true,
             "haveContent" => true,
             "content" => $products], 'json');

            return new JsonResponse($response, 200, [], true);
        }
        else
        {
            try
            {
                $products = $this->getDoctrine()->getRepository(Product::class)->findByBrand($brandName);

                if ($products)
                {
                    // converti au format json
                    $response = $this->_serializer('brand')->serialize(["success" => true,
                    "haveContent" => true,
                    "content" => $products], 'json');

                    // met réusltat en cache
                    $productList = $this->_cache->getItem($brandNameSlug.'_productList');
                    $productList->set($products);
                    //$productList->expiresAfter(86400);
                    $this->_cache->save($productList);

                    return new JsonResponse($response, 200, [], true);
                }
                else
                {
                    $response = $this->_serializer()->serialize(['success' => true,
                    'haveContent' => false,
                    'message' => 'no products found'], 'json');
                    return new JsonResponse($response, 200, [], true);
                }
            }
            catch (\Exception $e)
            {
                $response = $this->_serializer()->serialize(['success' => false,
                    'haveContent' => false,
                    "error" => $e,
                    'message' => 'fall to load autocomplete data'], 'json');
                return new JsonResponse($response, 200, [], true);
            }
        }
    }


    /**
     * recherche tous les parfums de la marque $brandName et qui ont pour nom $productName. si au moins un résultat est trouvé, il est mis en cache avec la clé 'parfum_$productName_found_for_$brandName' pendant 24h.
     *
     * @param Request $request
     * @return void
     */
    public function searchByName(Request $request) {

        $brandName  = $this->_isValid( $request->request->get('brand') );
        $productName  = $this->_isValid( $request->request->get('product') );

        $productNameSlug = $this->_slugifier->slugify($productName);

        if ($this->_cache->hasItem('parfum_'.$productNameSlug.'_found_for_'.$brandName))
        {
            $products = $this->_cache->getItem('parfum_'.$productNameSlug.'_found_for_'.$brandName)->get();

            // sérialize en json
            $response =  $this->_serializer('brand')->serialize(["success" => true,
            "haveContent" => true,
            "content" => $products], 'json');

            return new JsonResponse($response, 200, [], true);

        }
        else
        {
            try
            {
                // tous les produits de la marque $brnadName et qui ont pour nom $productName
                $products = $this->getDoctrine()->getRepository(Product::class)->findByNameAndBrand($productName, $brandName);

                if ($products)
                {
                    // serialise en json
                    $response =  $this->_serializer('brand')->serialize(["success" => true,
                    "haveContent" => true,
                    "content" => $products], 'json');

                    // met le resultat en cache
                    $searchResult = $this->_cache->getItem('parfum_'.$productNameSlug.'_found_for_'.$brandName);
                    $searchResult->set($products);
                    $searchResult->expiresAfter(86400);
                    $this->_cache->save($searchResult);

                    //retourne le résultat
                    return new JsonResponse($response, 200, [], true);
                }
                else
                {

                    $response = $this->_serializer()->serialize(['success' => true,
                    'haveContent' => false,
                    'message' => "<div><h2>Aucun résultat ne correspond à votre recherche</h2></div>"], 'json');

                    // on enregistre le nom de la marque et du produit non trouvé dans la table not_found
                    $notFound = new NotFound();
                    $notFound->setBrand($brandName);
                    $notFound->setProduct($productName);
                    
                    $em = $this->getDoctrine()->getManager();

                    $em->persist($notFound);
                    $em->flush();

                    return new JsonResponse($response, 200, [], true);
                }
            }
            catch (\Exception $e)
            {
                $response = $this->_serializer()->serialize(['success' => false,
                    'haveContent' => false,
                    "error"=> $e,
                    'message' => '<div><h2 class="alert alert-warnning">Impossible to execute this request</h2></div>'], 'json');

                return new JsonResponse($response, 200, [], true);
            }
        }
    }


    /**
     * Reherche tous les produits de la marque $brandName et appartenant à la famille de note $familyNote. si au moins un resultat est trouvé, il mis en cache avec la clé 'parfum_$familyNote_found_for_$brandName'pendant 24h.
     *
     * @param Request $request Les paramètres de la requête ajax
     * @return void
     */
    public function searchByFamilyNote(Request $request) {

        $brandName  = $this->_isValid( $request->request->get('brand') );
        $familyNote  = $this->_isValid( $request->request->get('familyNote') );

        $familyNoteSlug = $this->_slugifier->slugify($familyNote);

        if ($this->_cache->hasItem('parfum_'.$familyNoteSlug.'_found_for_'.$brandName))
        {
            $products = $this->_cache->getItem('parfum_'.$familyNoteSlug.'_found_for_'.$brandName)->get();

            // serialise en json
            $response = $this->_serializer('brand')->serialize(["success" => true,
            "haveContent" => true,
            "content" => $products], 'json');

            return new JsonResponse($response, 200, [], true);

        }
        else
        {
            try
            {
                // tous les produits de la marque $brnadName et qui ont pour nom $productName
                $products = $this->getDoctrine()->getRepository(Product::class)->findByFamilyNoteAndBrand($familyNote, $brandName);

                if ($products)
                {
                    // serialise en json
                    $response = $this->_serializer('brand')->serialize(["success" => true,
                    "haveContent" => true,
                    "content" => $products], 'json');

                    // met le resultat en cache
                    $searchResult = $this->_cache->getItem('parfum_'.$familyNoteSlug.'_found_for_'.$brandName);
                    $searchResult->set($products);
                    $searchResult->expiresAfter(86400);
                    $this->_cache->save($searchResult);

                    //retourne le résultat
                    return new JsonResponse($response, 200, [], true);
                }
                else
                {

                    $response = $this->_serializer()->serialize(['success' => true,
                    'haveContent' => false,
                    'message' => "<div><h2>Aucun résultat ne correspond à votre recherche</h2></div>"], 'json');
                    return new JsonResponse($response, 200, [], true);
                }
            }
            catch (\Exception $e)
            {
                $response = $this->_serializer()->serialize(['success' => false,
                    'haveContent' => false,
                    'error'=> $e,
                    'message' => '<div><h2 class="alert alert-warnning">Impossible to execute this request</h2></div>'], 'json');
                return new JsonResponse($response, 200, [], true);
            }
        }
    }

    
    /**
     * recherche tous les parfums de marsque $brandName et qui contiennent au moins une des notes contenue dans $searchNotes. Trie les parfums par ordre de pertinence des notes qu'ils contiennent par rapport aux notes recherchées
     *
     * @param Request $request
     * @return void 
     */
	public function searchByNote(Request $request) {
        $brandName  = $this->_isValid( $request->request->get('brand') );
        $notes = $request->request->get('note');

        $notesSlug = $this->_slugifier->slugify($notes);
        
        $searchNotes  = explode( ",", $notes );
        $searchNotes  = $this->_isValid( $searchNotes );


	        if ($this->_cache->hasItem('parfum_found_for_'.$notesSlug.'_and_'.$brandName))
	        {
                $products = $this->_cache->getItem('parfum_found_for_'.$notesSlug.'_and_'.$brandName)->get();
                
                // serialise en json
                $response = $this->_serializer('brand')->serialize(["success"=>true, "haveContent"=>true, "content"=> $products], 'json');

	            return new JsonResponse($response, 200, [], true);

	        }
	        else
	        {
	            try
	            {
                    // tous les parfums de la marque $brandName et qui contiennent au moins une note recherchée dans $searchNotes
                    
                    $products = []; 

                    foreach ($searchNotes as $note) 
                    {
                        $productsFound = $this->getDoctrine()->getRepository(Product::class)->findByNoteAndBrand($note, $brandName);
                        foreach ($productsFound as $product) 
                        {
                            if (in_array(["score"=>null, "product"=>$product], $products, true) === false) {
                                array_push($products, ["score"=>null, "product"=>$product]);
                            }
                        }
                    }
    
                    // attribut un score à chaque parfum trouvé en fonction du nombre de notes qu'il contient par rapport aux notes recherchées
                    foreach ($products as $key=>&$product) 
                    {
                        $containedNotes = explode(",", $products[$key]["product"]->getNotes());
                        $containedNotes = $this->_isValid($containedNotes);
                        
                        $commonNotes = array_intersect( $containedNotes, $searchNotes);
                        
                        if ($commonNotes) 
                        {
                            $score = (count($commonNotes) / count($searchNotes)) * (count($commonNotes) / count($containedNotes)) * 100;
                            $products[$key]["score"] = $score;
                        }
                    }
                    unset($product);
                        
    
                      
                    
	                if ($products)
	                {
                       
	                    // serialise en json
                        $response = $this->_serializer('brand')->serialize(["success"=>true, "haveContent"=>true, "content"=> $products], 'json');

	                    // met le resultat en cache
                        $searchResult = $this->_cache->getItem('parfum_found_for_'.$notesSlug.'_and_'.$brandName);
	                    $searchResult->set($products);
	                    $searchResult->expiresAfter(1800);
	                    $this->_cache->save($searchResult);
                        
                        // retourne le résultat
	                    return new JsonResponse($response, 200, [], true);
	                }
	                else
	                {

	                    $response = $this->_serializer()->serialize(['success' => true,
	                    'haveContent' => false,
	                    'message' => "<div><h2>Aucun résultat ne correspond à votre recherche</h2></div>"], 'json');
	                    return new JsonResponse($response, 200, [], true);
	                }
	            }
	            catch (\Exception $e)
	            {
	                $response = $this->_serializer("brand")->serialize(
                        ['success' => false,
                        'haveContent' => false,
                        "error" => $e,
                        'message' => '<div><h2 class="alert alert-warnning">Request can nt be execute</h2></div>'
                    ], 
                    'json');
	                return new JsonResponse($response, 200, [], true);
	            }
            }
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
            foreach ($data as $k=>&$v)
            {
                $data[$k] = strtolower($v);
                $data[$k] = strip_tags($v);
                $data[$k] = stripslashes($v);
                $data[$k] = trim($v);
            }

            unset($v);
            return $data;
        }
        else
        {
            $data = strtolower($data);
            $data = strip_tags($data);
            $data = stripslashes($data);
            $data = trim($data);

            return $data;
        }
    }

    /**
     * objet serializer
     *
     * @param [type] $entityName le nom de l'entity concerné par la requête
     * @return void
     */
    private function _serializer($object = null) {

        $encoders = array(new JsonEncoder());

        if  ($object != null)
        {
            $normalizers = array((new ObjectNormalizer())->setIgnoredAttributes([$object]));
            // $normalisers->setCircularReferenceHandler(function ($object) {
            //     return $object->getName();
            // });
        }
        else
        {
            $normalizers = array((new ObjectNormalizer()));//$normalizers->setCircularReferenceLimit(1000000000000000000000000000000000000000000000000);
        }

        $serializer = new Serializer($normalizers, $encoders);

        return $serializer;
    }

}
