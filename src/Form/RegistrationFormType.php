<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',EmailType::class,[
                 'attr' => [
                    'class' =>'form-control'
                 ],
                 'label'   =>'E-mail : '
            ])
            ->add('Nom',TextType::class,[
                'attr' => [
                    'class' =>'form-control'
                 ] ,
                 'label' =>'Nom : '

            ])
            ->add('Prenom',TextType::class,[
                'attr' => [
                    'class' =>'form-control'
                ],
                'label' => 'Prénom : '

            ])
            ->add('Numtel', TextType::class,[
                'attr' => [
                    'class' =>'form-control'
                ],
                'label' => 'Numéro de téléphone : '

            ])
           
            ->add('Nationnalite', ChoiceType::class, [
                'label' => 'Nationalité',
                'choices' => $this->getCountries(),
                'attr' => ['class' => 'form-control'],
                'placeholder' => 'Sélectionnez votre pays', // Optional placeholder
            ])

            
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
                'label' => 'En n\'inscrivant à ce cite, j\'accepte les termes d\'utilisation'
            ])
            ->add('plainPassword', PasswordType::class, [
                // This field is not mapped directly to the entity
                'mapped' => false,
                
                // HTML attributes for better UX and security
                'attr' => [
                    'autocomplete' => 'new-password', // Disable autocomplete
                    'class' => 'form-control', // Bootstrap class for styling
                    'placeholder' => 'Enter a strong password', // Placeholder text
                    'aria-label' => 'Password', // Accessibility improvement
                ],
                
                // Validation constraints for stronger passwords
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password.',
                    ]),
                    new Length([
                        'min' => 12, // Increased minimum length for better security
                        'minMessage' => 'Your password should be at least {{ limit }} characters long.',
                        'max' => 4096, // Maximum length allowed by Symfony
                    ]),
                    new Regex([
                        'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{12,}$/',
                        'message' => 'Your password must include at least one uppercase letter, one lowercase letter, one number, and one special character.',
                    ]),
                ],
                
                // Label and help text for better user guidance
                'label' => 'Password:',
                'help' => 'Your password must be at least 12 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.',
                
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

    private function getCountries(): array
{
    return [        
        'Algeria' => 'DZ',
        'France' => 'FR',
        'Maroc' => 'MA',
        'Tunisia' => 'TN',
        'Egypte' => 'EG',
    ];
}

}
