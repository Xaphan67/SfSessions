<?php

namespace App\Form;

use App\Entity\Formateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FormateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prenom', TextType::class, [
                'label' => 'Prénom *',
                'attr' => [
                    'class' => 'form-input'
                ]
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom *',
                'attr' => [
                    'class' => 'form-input'
                ]
            ])
            ->add('sexe', ChoiceType::class, [
                'label' => 'Sexe *',
                'placeholder' => 'Veuillez choisir...',
                'choices'  => [
                    'Homme' => "M",
                    'Femme' => "F",
                ],
                'attr' => [
                    'class' => 'form-input'
                ]
            ])
            ->add('dateNaissance', DateType::class, [
                'label' => 'Date de naissance *',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-input'
                ]
            ])
            ->add('ville', TextType::class, [
                'label' => 'Ville *',
                'attr' => [
                    'class' => 'form-input'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email *',
                'attr' => [
                    'class' => 'form-input'
                ]
            ])
            ->add('telephone', TextType::class, [
                'label' => 'Téléphone *',
                'attr' => [
                    'class' => 'form-input'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-form'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formateur::class,
        ]);
    }
}
