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
use App\Entity\User;
use App\Repository\UserRepository;


class RendezVousType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        
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
        
        
        
        ->add('jour', DateType::class, [
            'widget' => 'single_text',
            'html5' => true,
            
        ])
        ->add('heureString', HiddenType::class, [
            'mapped' => true, // ✅ Stocke bien la valeur dans l'entité
        ])
        

        // ->add('heureString', ChoiceType::class, [
        //     'choices' => [], // Rempli dynamiquement via AJAX
        //     'placeholder' => 'Sélectionnez une heure disponible',
        //     'required' => true,
        //     'mapped' => false, //  Symfony ne va pas le mapper automatiquement
        //     'attr' => ['class' => 'form-control']
        // ])
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
            'available_times' => [], // Option pour stocker les créneaux horaires du médecin
        ]);
    }
}
