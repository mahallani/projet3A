<?php

namespace App\Form;

use App\Entity\Disponibilite;
use App\Entity\RendezVous;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
<<<<<<< HEAD
use App\Entity\User;
use App\Repository\UserRepository;
=======

>>>>>>> dd238d0bf7bf109232031f042ecf1572bb2c662b


class RendezVousType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
<<<<<<< HEAD
        
        ->add('idMedecin', EntityType::class, [
            'class' => User::class,
            'choice_label' => 'nom',
            'placeholder' => 'Sélectionnez un médecin',
            'query_builder' => function (UserRepository $userRepository) {
                return $userRepository->createQueryBuilder('u')
                    ->where('u.roles LIKE :role')
                    ->setParameter('role', '%ROLE_MEDECIN%');
            },
            'attr' => ['class' => 'form-select'],
        ])
        
        
        
=======
        ->add('idMedecin', IntegerType::class, [
            'required' => true,
            'attr' => ['class' => 'form-control', 'placeholder' => 'ID du médecin']
        ])
        
>>>>>>> dd238d0bf7bf109232031f042ecf1572bb2c662b
        ->add('jour', DateType::class, [
            'widget' => 'single_text',
            'html5' => true,
            
        ])
        ->add('heureString', HiddenType::class, [
<<<<<<< HEAD
            'mapped' => true, // ✅ Stocke bien la valeur dans l'entité
        ])
        

        // ->add('heureString', ChoiceType::class, [
        //     'choices' => [], // Rempli dynamiquement via AJAX
        //     'placeholder' => 'Sélectionnez une heure disponible',
        //     'required' => true,
        //     'mapped' => false, //  Symfony ne va pas le mapper automatiquement
        //     'attr' => ['class' => 'form-control']
        // ])
=======
            'mapped' => true, 
        ])
        

   
>>>>>>> dd238d0bf7bf109232031f042ecf1572bb2c662b
        ->add('motif')
        ->add('symptomes')
        ->add('traitementEnCours')
        ->add('notes')    
         ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RendezVous::class,
<<<<<<< HEAD
            'available_times' => [], // Option pour stocker les créneaux horaires du médecin
=======
            'available_times' => [], 
>>>>>>> dd238d0bf7bf109232031f042ecf1572bb2c662b
        ]);
    }
}
