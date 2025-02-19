<?php

namespace App\Form;

use App\Entity\SuiviBebe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Validator\Constraints as Assert;

class SuiviBebeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('date_suivi', DateTimeType::class, [
            'widget' => 'single_text',
            'required' => true,
            'constraints' => [
                new Assert\NotBlank([
                    'message' => 'La date de suivi est obligatoire.',
                ]),
                new Assert\LessThanOrEqual([
                    'value' => new \DateTime(), 
                    'message' => 'La date ne peut pas être dans le futur.'
                ]),
            ],
            'empty_data' => (new \DateTime())->format('Y-m-d'), // Correction ici
        ])
            ->add('poids_bebe', NumberType::class, [
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le poids est obligatoire.']),
                    new Assert\Range([
                        'min' => 0.5,
                        'max' => 10,
                        'notInRangeMessage' => 'Le poids doit être compris entre 0.5 kg et 10 kg.'
                    ])
                ]
            ])
            ->add('taille_bebe', NumberType::class, [
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La taille est obligatoire.']),
                    new Assert\Range([
                        'min' => 30,
                        'max' => 70,
                        'notInRangeMessage' => 'La taille doit être comprise entre 30 cm et 70 cm.'
                    ])
                ]
            ])
            ->add('etat_sante', ChoiceType::class, [
                'choices' => [
                    'Bien' => 'bien',
                    'Moyennement bien' => 'moyennement_bien',
                    'Malade' => 'malade',
                ],
                'placeholder' => 'Sélectionner l\'état de santé',
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Veuillez sélectionner un état de santé.'])
                ],
            ])
            ->add('appetit_bebe', ChoiceType::class, [
                'choices' => [
                    'Normal' => 'Normal',
                    'Diminution d’appétit' => 'Diminution d’appétit',
                    'Augmentation d’appétit' => 'Augmentation d’appétit',
                    'Refus de s’alimenter' => 'Refus de s’alimenter',
                ],
                'placeholder' => 'Sélectionnez l\'appétit du bébé',
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Veuillez sélectionner l\'appétit du bébé.'])
                ],
            ])
            ->add('battement_coeur', NumberType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\Range([
                        'min' => 60,
                        'max' => 200,
                        'notInRangeMessage' => 'Les battements du cœur doivent être entre 60 et 200 bpm.'
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SuiviBebe::class,
        ]);
    }
}
