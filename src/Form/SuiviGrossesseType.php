<?php

namespace App\Form;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Entity\SuiviGrossesse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
class SuiviGrossesseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('dateSuivi', DateType::class, [
            'widget' => 'single_text',
            'required' => false,
            'constraints' => [
                new NotBlank([
                    'message' => 'La date de suivi est obligatoire.',
                ]),
                new LessThanOrEqual([
                    'value' => new \DateTime(), // Compare avec la date actuelle
                    'message' => 'La date de suivi ne peut pas être dans le futur.',
                ]),
            ],
            'empty_data' => (new \DateTime())->format('Y-m-d'), // Remplacer si aucune valeur n'est entrée
        ])
            ->add('poids', NumberType::class, [
                'label' => 'Poids (kg)',
                'attr' => [
                    'class' => 'form-control',
                    'step' => '0.1', // Permet d'entrer des décimales
                    'placeholder' => 'Entrez le poids...',
                ],
            ])
            ->add('tension', NumberType::class, [
                'required' => false,
            ])
            ->add('symptomes', TextareaType::class, [
                'label' => 'Symptômes',
                'attr' => [
                    'class' => 'form-control', // Pour le style Bootstrap
                    'rows' => 5, // Nombre de lignes visibles
                    'placeholder' => 'Décris les symptômes ici...', 
                ],
            ])
            ->add('etatGrossesse', ChoiceType::class, [
                'choices' => [
                    'Normal' => 'Normal',
                    'À risque' => 'À risque',
                    'Critique' => 'Critique',
                ],
                'placeholder' => 'Sélectionnez un état de grossesse',
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SuiviGrossesse::class,
        ]);
    }
}
