<?php

namespace App\Form;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use App\Entity\Disponibilite;



class DisponibiliteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
           
            ->add('jour', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Jour',
            ])
            ->add('heuresDisp', CollectionType::class, [
                'entry_type' => ChoiceType::class,
                'entry_options' => [
                    'choices' => [
                        '09:00 - 11:00' => '09:00-11:00',
                        '11:00 - 13:00' => '11:00-13:00',
                        '14:00 - 16:00' => '14:00-16:00',
                        '16:00 - 18:00' => '16:00-18:00',
                    ],
                    'label' => false,
                ],
                'allow_add' => true, 
                'allow_delete' => true, 
                'by_reference' => false,
                'prototype' => true, 
            ])
        
    ->add('statutDisp', ChoiceType::class, [
        'choices'  => [
            'Disponible' => 'Disponible',
            'Indisponible' => 'Indisponible',
        ],
        'expanded' => true,  
        'multiple' => false,
    ])

            ->add('idMedecin', TextType::class, [
                'label' => 'ID du Médecin',
                'attr' => [
                    'placeholder' => 'Entrez l\'ID du médecin'
                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Ajouter',
                'attr' => ['class' => 'btn btn-success d-none'], 
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Disponibilite::class,
        ]);
    }
}
