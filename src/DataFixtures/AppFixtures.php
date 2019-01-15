<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
Use App\Entity\Brand;
Use App\Entity\Product;
Use Faker;


class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create();

        $brands = array("Christian Dior", "Givenchy", "Chanel", "Giorgio Armani", "Dolce Gabanna", "Versace", "kenzo", "Tom Ford");

        $products = array("Sauvage", "Eau sauvage", "L'interdit", "Gentleman", "L'homme idéal", "J'adore", "Joy", "Light Blue", "Oud", "Portofino", "Wanted", "Aqua di gio", "La nuit de l'homme", "Miss Dior", "Si", "Coco moidemoiselle", "Very Irresistible", "Angel");


        foreach ($brands as $v) {
            $brand = new Brand();
            $brand->setName($v);
            $brand->setSlug();
            $manager->persist($brand);


            for($i=0; $i<2; $i++){
                
                $product = new Product();
                $name = $faker->word;
                $product->setName($name)
                        ->setSlug()
                        ->setBrand($brand)
                        ->setType("eau de toilette")
                        ->setGender("female")
                        ->setCreatedAt($faker->dateTime($max = 'now', $timezone = null))
                        ->setBaseNotes($faker->words($nb = 3, $asText = true) )
                        ->setTopNotes($faker->words($nb = 3, $asText = true) )
                        ->setHeartNotes($faker->words($nb = 3, $asText = true) )
                        ->setFamilyNotes($faker->words($nb = 1, $asText = true));
                    
                $manager->persist($product);
            }
        }
        $manager->flush();
    }
}
