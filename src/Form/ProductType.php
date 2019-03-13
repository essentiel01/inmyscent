<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Entity\Product;
use App\Entity\Brand;

use Doctrine\ORM\EntityRepository;

// types
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
// validation
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;


class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, 
                [
                    "label" => "Nom du parfum", 
                    "constraints" => [
                        new NotBlank(["message" => "Ce champ ne peut être vide"]),
                        new Length([
                            "min" => 2, 
                            "minMessage" => "Ce champ doit contenir au moins {{ limit }} caractères"
                        ])
                    ]
                ]
            )
            ->add('gender', ChoiceType::class,
                [
                    "label" => "Genre",
                    "choices" => [
                        "Masculin" => "m",
                        "Féminin" => "f",
                        "Mixte" => "mixte"
                    ],
                    "constraints" => new NotBlank(["message" => "Ce champ ne peut être vide"])
                ]
            )
            ->add('type', TextType::class, 
                [
                    "label" => "Concentration",
                    "constraints" => [
                        new NotBlank(["message" => "Ce champ ne peut être vide"]),
                        new Length([
                            "min" => 3, 
                            "minMessage" => "Ce champ doit contenir au moins {{ limit }} caractères"
                        ])
                    ]
                ]
            )
            ->add('familyNotes', TextType::class,
                [
                    "label" => "Famille de notes",
                    "constraints" => new Length([
                        "min" => 3,
                        "minMessage" => "Ce champ doit contenir au moins {{ limit }} caractères"
                    ])
                ]
            )
            ->add('topNotes', TextType::class,
                [
                    "label" => "Notes de tête",
                    "required" => FALSE,
                    "constraints" => new Length([
                        "min" => 3,
                        "minMessage" => "Ce champ doit contenir au moins {{ limit }} caractères"
                    ])
                ]
            )
            ->add('heartNotes', TextType::class,
                [
                    "label" => "Notes de coeur",
                    "required" => FALSE,
                    "constraints" => new Length([
                        "min" => 3,
                        "minMessage" => "Ce champ doit contenir au moins {{ limit }} caractères"
                    ])
                ]
            )
            ->add('baseNotes', TextType::class,
                [
                    "label" => "Notes de base",
                    "required" => FALSE,
                    "constraints" => new Length([
                        "min" => 3,
                        "minMessage" => "Ce champ doit contenir au moins {{ limit }} caractères"
                    ])
                ]
            )

            ->add('notes', TextType::class,
                [
                    "label" => "Notes",
                    "required" => FALSE,
                    "constraints" => new Length([
                        "min" => 3,
                        "minMessage" => "Ce champ doit contenir au moins {{ limit }} caractères"
                    ])
                ]
            )
            ->add('brand', EntityType::class, 
                [
                    "choice_label" => "name",
                    "class" => Brand::class,
                    "placeholder" => "Sélectionner une marque",
                    "constraints" => new NotBlank(["message" => "Ce champ ne peut être vide"])
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
