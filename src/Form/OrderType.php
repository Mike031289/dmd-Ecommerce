<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Carrier;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('addresses', EntityType::class, [
                'label' => '<h6 class="text-muted">Choisissez votre adresse de livraison</h6>',
                'required' => true,
                'class'=> Address::class,
                'expanded' => true,
                'choices' => $options['addresses'],
                'label_html' => true,
            ])
            ->add('carrier', EntityType::class, [
                'label' => '<h6 class="text-muted"> Choisissez votre transporteur</h6>',
                'required' => true,
                'class'=> Carrier::class,
                'expanded' => true,
                'label_html' => true,
            ])
            ->add('submit', \Symfony\Component\Form\Extension\Core\Type\SubmitType::class, [
                'label' => '<i data-feather="check-circle"></i> Valider ma commande',
                'label_html' => true,
                'attr' => [
                    'class' => 'btn btn-success w-100 d-flex align-items-center justify-content-center gap-1',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'addresses' => null, // Default value if not provided
        ]);
    }
}