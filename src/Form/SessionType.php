<?php

namespace App\Form;

use App\Entity\Session;
use App\Entity\Formateur;
use App\Entity\Formation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class SessionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom *',
                'attr' => [
                    'class' => 'form-input'
                ]
            ])
            ->add('places', IntegerType::class, [
                'label' => 'Places *',
                'attr' => [
                    'class' => 'form-input',
                    'min' => 1
                ]
            ])
            ->add('dateDebut', DateType::class, [
                'label' => 'Date de début *',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-input'
                ]
            ])
            ->add('dateFin', DateType::class, [
                'label' => 'Date de fin *',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-input'
                ]
            ])
            ->add('formation', EntityType::class, [
                'label' => 'Formation *',
                'class' => Formation::class,
                'attr' => [
                    'class' => 'form-input'
                ]
            ])
            ->add('formateur', EntityType::class, [
                'label' => 'Formateur *',
                'class' => Formateur::class,
                'attr' => [
                    'class' => 'form-input'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-form'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Session::class,
            'choice_label' => 'nom'
        ]);
    }
}
