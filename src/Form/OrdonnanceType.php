<?php


namespace App\Form;

use App\Entity\Ordonnance;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType; 
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrdonnanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('medicament', null, [
                'required' => true,
                'label' => 'Médicament',
                'attr' => [
                    'placeholder' => 'Entrez le nom du médicament',
                ],
            ])
            ->add('posologie', null, [
                'required' => true,
                'label' => 'Posologie',
                'attr' => [
                    'placeholder' => 'Entrez la posologie',
                ],
            ])
            ->add('date_prescription', DateType::class, [ // Use DateType::class
                'widget' => 'single_text', // Use a single text input for the date
                'html5' => true, // Use HTML5 date input
                'required' => true,
                'label' => 'Date de prescription',
                'attr' => [
                    'placeholder' => 'YYYY-MM-DD',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ordonnance::class,
        ]);
    }
}