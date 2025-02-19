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



class RendezVousType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('idMedecin', IntegerType::class, [
            'required' => true,
            'attr' => ['class' => 'form-control', 'placeholder' => 'ID du médecin']
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
        //     'mapped' => false, // 🔥 Symfony ne va pas le mapper automatiquement
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
