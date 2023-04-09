<?php

namespace App\Form;

use App\Entity\Contact;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Votre nom et prénom'
                ]
            ])
            ->add('email',EmailType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Votre adresse email'
                ]
            ])
            ->add('phone',IntegerType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Votre numéro de téléphone'
                ]
            ])
            ->add('subject',TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Sujet de votre message'
                ]
            ])
            ->add('message',TextareaType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Votre message'
                ],
                'row_attr' => [
                    'class' => 'form-group'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
