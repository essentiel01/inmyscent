<?php

namespace App\Form;

use App\Entity\Brand;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
// types
use Symfony\Component\Form\Extension\Core\Type\TextType;
// validation
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;



class BrandType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, 
                [
                    "label" => "Nom de la marque", 
                    "constraints" => [
                        new NotBlank(["message"=>"Ce champ ne peut être vide"]),
                        new Length(["min"=> 3, "minMessage"=>"le nom de la marque doit contenir au moins {{ limit }} caractères"])
                        ]
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Brand::class,
        ]);
    }
}
