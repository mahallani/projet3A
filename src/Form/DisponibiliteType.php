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
<<<<<<< HEAD
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\User;
use App\Repository\UserRepository;

=======
>>>>>>> dd238d0bf7bf109232031f042ecf1572bb2c662b



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
<<<<<<< HEAD
                    'label' => false, // Supprime le label pour éviter la duplication
                ],
                'allow_add' => true,  // Permet d'ajouter dynamiquement
                'allow_delete' => true, // Permet de supprimer un créneau
                'by_reference' => false,
                'prototype' => true, // Active le prototype pour JavaScript
=======
                    'label' => false,
                ],
                'allow_add' => true, 
                'allow_delete' => true, 
                'by_reference' => false,
                'prototype' => true, 
>>>>>>> dd238d0bf7bf109232031f042ecf1572bb2c662b
            ])
        
    ->add('statutDisp', ChoiceType::class, [
        'choices'  => [
            'Disponible' => 'Disponible',
            'Indisponible' => 'Indisponible',
        ],
<<<<<<< HEAD
        'expanded' => true,  // Affiche comme boutons radio (facultatif)
        'multiple' => false, // Un seul choix possible
    ])
    ->add('idMedecin', EntityType::class, [
        'class' => User::class,
        'choice_label' => 'nom',
        'query_builder' => function (UserRepository $er) use ($options) {
            // If the user is a doctor, only show the logged-in user
            if ($options['isMedecin']) {
                return $er->createQueryBuilder('u')
                    ->where('u.id = :userId')
                    ->setParameter('userId', $options['user']->getId());
            }

            // If the user is an admin, show all doctors
            return $er->createQueryBuilder('u')
                ->where('u.roles LIKE :role')
                ->setParameter('role', '%ROLE_MEDECIN%');
        },
        'placeholder' => 'Sélectionnez un médecin',
        'label' => 'Médecin',
        'attr' => ['class' => 'form-control'],
    ])
            ->add('save', SubmitType::class, [
                'label' => 'Ajouter',
                'attr' => ['class' => 'btn btn-success d-none'], // Caché par défaut
=======
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
>>>>>>> dd238d0bf7bf109232031f042ecf1572bb2c662b
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Disponibilite::class,
<<<<<<< HEAD
            'user' => null, // To pass the user from the controller
            'isMedecin' => false,
=======
>>>>>>> dd238d0bf7bf109232031f042ecf1572bb2c662b
        ]);
    }
}
