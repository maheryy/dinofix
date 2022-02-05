<?php

namespace App\Form;

use App\Entity\RequestActive;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RequestActiveType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content')
            ->add('status')
            ->add('created_at')
            ->add('updated_at')
            ->add('request')
            ->add('fixer')
            ->add('step')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RequestActive::class,
        ]);
    }
}
