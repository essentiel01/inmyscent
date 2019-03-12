<?php

namespace App\EventListener;


use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use App\Entity\Brand;
use App\Entity\Product;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;

use Cocur\Slugify\Slugify;


class PostPersistListener
{
    protected $cache;

    public function __construct()
    {
        $this->_cache = new FilesystemAdapter();
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        // quand on ajoute une nouvelle marque, la liste des marques qui est stockée dans le cache devient obsolète et on l'invalide
        if ($entity instanceof Brand) {
            if ($this->_cache->hasItem("brandList")) 
            {
                $this->_cache->deleteItem('brandList');
            }
        }

        if ($entity instanceof Product) {
            $brandNameSlug = ( $entity->getBrand() )->getSlug();
            $brandName = ( $entity->getBrand() )->getName();
            $familyNoteSlug = ( new Slugify() )->slugify( $entity->getFamilyNotes() );
            $productNameSlug = $entity->getSlug();

            // quand on ajoute un nouveau produit, si ce produit appartient à une marque dont la liste des produits est stockée dans le cache alors cette liste devient obsolète et on l'invalide
            if ($this->_cache->hasItem($brandNameSlug . "_productList")) 
            {
                $this->_cache->deleteItem($brandNameSlug . "_productList");
            }

             // quand on ajoute un nouveau produit, si ce produit appartient à une marque et à une famille de note dont la liste des produits trouvés lors d'une recherche antérieure est stockée dans le cache alors cette liste devient obsolète et on l'invalide
            if ($this->_cache->hasItem('parfum_'.$familyNoteSlug.'_found_for_'.$brandName)) 
            {
                $this->_cache->deleteItem('parfum_'.$familyNoteSlug.'_found_for_'.$brandName);
            }

            // quand on ajoute un produit , si le nom de ce produit avait déjà fait l'objet d'une recherche dont le résutat est stocké dans le cache alors ce résultat devient obsolète et on l'invalide.
            if ($this->_cache->hasItem('parfum_'.$productNameSlug.'_found_for_'.$brandName)) 
            {
                $this->_cache->deleteItem('parfum_'.$productNameSlug.'_found_for_'.$brandName);
            }
        }

    }
}