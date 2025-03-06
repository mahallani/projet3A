<?php

namespace App\Form;

use App\Entity\Ordonnance;
use App\Entity\Traitement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TraitementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('datePrescription', null, [
                'widget' => 'single_text',
                'label' => 'Date de prescription',
            ])
            ->add('historiqueTraitement', null, [
                'label' => 'Historique du traitement',
            ])
            ->add('ordonnance', EntityType::class, [
                'class' => Ordonnance::class,
                'choice_label' => 'id',
                'label' => 'Ordonnance',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Traitement::class,
        ]);
    }
}