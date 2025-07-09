<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Votre prénom',
                'attr' => [
                    'Placeholder' => 'Indiquez votre prénom'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Votre nom',
                'attr' => [
                    'Placeholder' => 'Indiquez votre nom'
                ]
            ])
            ->add('address', TextType::class, [
                'label' => 'Votre adresse',
                'attr' => [
                    'Placeholder' => 'Indiquez votre adresse'
                ]
            ])
            ->add('postal', TextType::class, [
                'label' => 'Votre code postal',
                'attr' => [
                    'Placeholder' => 'Indiquez votre code postal'
                ]
            ])
            ->add('city', TextType::class, [
                'label' => 'Votre ville',
                'attr' => [
                    'Placeholder' => 'Indiquez votre ville'
                ]
            ])
            ->add('country', CountryType::class, [
                'label' => 'Votre pays',
                'attr' => [
                    'Placeholder' => 'Indiquez votre pays'
                ]
            ])
            ->add('phone', TextType::class, [
                'label' => 'Votre téléphone',
                'attr' => [
                    'Placeholder' => 'Indiquez votre numéro de téléphone'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Sauvegarder",
                'attr'  => [
                    'class' => "btn btn-success"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}