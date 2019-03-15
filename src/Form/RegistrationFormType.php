<?php
// formulaire d'enregistrement personalisé pour les utilisateurs. remplace le formulaire paar défaut de fosuserbundle.

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


// types
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
// validation
use Symfony\Component\Validator\Constraints\NotBlank;


class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, array('label' => 'Adresse email', 'translation_domain' => 'FOSUserBundle'))
            ->add("sex", ChoiceType::class, array(
                    'label' => '',
                    'choices' => array('male'=>'m', 'female'=>'f'),
                    'expanded' => true,
                    'constraints' => array( 
                        new NotBlank(array(
                            'message'=>'Ce champ est obligatoire'
                            )
                        )
                    )
                )
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\User',
            'csrf_token_id' => 'registration',
        ));
    }

    // public function getParent()
    // {
    //     return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    // }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }

    // For Symfony 2.x
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}