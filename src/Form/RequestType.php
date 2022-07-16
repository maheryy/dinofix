<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Dino;
use App\Entity\Request;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('subject', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Sujet',
                'required' => true,
            ])
            ->add('category', EntityType::class, [
                'label' => 'Catégorie du dinosaure',
                'class' => Category::class,
                'choice_label' => 'name',
                'required' => true,
            ])
            ->add('dino', EntityType::class, [
                'label' => 'Nom du dinosaure',
                'class' => Dino::class,
                'choice_label' => 'name',
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description de la panne',
                'required' => true,
            ])
            ->add('expected_at', DateType::class, [
                'label' => "Date d'intervention souhaitée",
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('save', SubmitType::class, [
                'attr' => ['class' => 'btn-success'],
                'label' => 'Envoyer',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Request::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'request',
        ]);
    }
}
