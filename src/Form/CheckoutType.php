<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Carrier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CheckoutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user']; // On récupère l'utilisateur connecté
        $builder
            ->add('address',EntityType::class, [
                'class' => Address::class,
                'choices' => $user->getAddresses(),
                'label' => 'Adresse de livraison',
                'expanded' => true,
                'multiple' => false,
                'required' => true,
                'label' => false
            ])
            ->add('carrier', EntityType::class, [
                'class' => Carrier::class,
                'label' => 'Transporteur',
                'expanded' => true,
                'multiple' => false,
                'required' => true,
                
            ])
            ->add('informations', TextareaType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Si vous avez des informations à nous communiquer, n\'hésitez pas à nous les indiquer ici'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'user' => [] // On ajoute un paramètre user
        ]);
    }
}
