<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName')
            ->add('company')
            ->add('address')
            ->add('complement')
            ->add('phone')
            ->add('city')
            ->add('codePostal')
            ->add('country', CountryType::class, [
                // J'ai ajouté les pays que je souhaite afficher dans le select en têtes de liste
                'preferred_choices' => ['FR', 'GB', 'US','DE','BE','CH','ES','IT','NL','PT','AT','LU','IE','DK','SE','NO','FI','CZ','PL','HU'],
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
