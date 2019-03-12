<?php

namespace App\EventListener;


use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use App\Entity\Brand;
use App\Entity\Product;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;

use Cocur\Slugify\Slugify;

class PostRemoveListener 
{

    protected $cache;

    public function __construct()
    {
        $this->_cache = new FilesystemAdapter();
    }

    public function postRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if ($entity instanceof Brand)
        {
            $brandNameSlug = $entity->getSlug();

            // quand on supprime une marque, la liste des marques stockée dans le cache devient obsolète et on l'invalide
            if ($this->_cache->hasItem('brandList')) 
            {
                $this->_cache->deleteItem('brandList');
            }

            // quand on supprime une marque, la liste des produits de cette marque stockée dans le cache devient obsolète et on l'invalide. Sachant qu'une marque ne peut pas être supprimé tant qu'elle contient des produits. cette condition sera toujours ignoré tant que les contraintes de restriction ne vont pas changée dans la base de donnée.
            if ($this->_cache->hasItem($brandNameSlug . '_productList')) 
            {
                $this->_cache->deleteItem($brandNameSlug . '_productList');
            }

        }

        if ($entity instanceof Product) 
        {
            $brandNameSlug = ( $entity->getBrand() )->getSlug();
            $brandName = ( $entity->getBrand() )->getName();
            $productNameSlug = $entity->getSlug();
            $familyNoteSlug = ( new Slugify() )->slugify( $entity->getFamilyNotes() );

            // quand on supprime un produit, si ce produit appartient à une marque dont la liste des produits est stockée dans le cache alors cette liste devient obsolète et on l'invalide
            if ($this->_cache->hasItem($brandNameSlug . '_productList')) 
            {
                $this->_cache->deleteItem($brandNameSlug . '_productList');
            }

            // quand on supprime un produit , si le nom de ce produit avait déjà fait l'objet d'une recherche dont le résutat est stocké dans le cache alors ce résultat devient obsolète et on l'invalide.
            if ($this->_cache->hasItem('parfum_'.$productNameSlug.'_found_for_'.$brandName)) 
            {
                $this->_cache->deleteItem('parfum_'.$productNameSlug.'_found_for_'.$brandName);
            }

            // quand on supprime un produit , si ce produit appartient à une famille de note qui avait déjà fait l'objet d'une recherche dont le résutat est stocké dans le cache alors ce résultat devient obsolète et on l'invalide.
            if ($this->_cache->hasItem('parfum_'.$familyNoteSlug.'_found_for_'.$brandName)) 
            {
                $this->_cache->deleteItem('parfum_'.$familyNoteSlug.'_found_for_'.$brandName);
            }
        }
    }
}
    
