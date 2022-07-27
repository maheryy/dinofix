<?php

namespace App\Form;

use App\Entity\Fixer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FixerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('picture', FileType::class, [
                'label' => 'Photo',
                'required' => true,
                'data_class' => null,
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/svg+xml',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger un fichier de type jpeg, png ou svg',
                    ]),
                ],
            ])
            ->add('firstname', TextType::class, [
                'label' =>  'Prénom',
            ])
            ->add('lastname', TextType::class, [
                'label' =>  'Nom',
            ])
            ->add('alias', TextType::class, [
                'label' =>  'Pseudonyme',
            ])
            ->add('email', EmailType::class, [
                'label' =>  'Adresse e-mail',
            ])
            ->add('phone', TelType::class, [
                'label' =>  'Téléphone',
                'required' => false,
                'constraints' => [
                    new Length([
                        'min' => 10,
                        'max' => 10,
                        'minMessage' => 'Votre numéro de téléphone doit contenir 10 chiffres',
                        'maxMessage' => 'Votre numéro de téléphone doit contenir 10 chiffres',
                    ]),
                ],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'required' => true,
                'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => ['class' => 'form-control']],
                'first_options' => [
                    'label' => 'Mot de passe',
                    'constraints' => [
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Your password should be at least {{ limit }} characters',
                            'max' => 4096,
                        ]),
                    ],
                ],
                'second_options' => [
                    'label' => 'Confirmation du mot de passe',
                ],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Fixer::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'fixer_item',
        ]);
    }
}
