<?php

namespace App\Form;

use App\Data\SearchData;
use App\Entity\Category;
use App\Entity\Dino;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchFilterType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', EntityType::class, [
                'label' => 'Catégorie',
                'required' => false,
                'class' => Category::class
            ])
            ->add('dinos', EntityType::class, [
                'label' => 'Dinosaure',
                'required' => false,
                'class' => Dino::class,
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('distance', ChoiceType::class, [
                'label' => 'Distance',
                'choices' => [
                    '< 5km' => 5,
                    '< 15km' => 15,
                    '< 30km' => 30,
                    '< 50km' => 50,
                ],
            ])
            ->add('reviews', ChoiceType::class, [
                'label' => 'Avis',
                'choices' => [
                    '1 étoile' => 1,
                    '2 étoiles' => 2,
                    '3 étoiles' => 3,
                    '4 étoiles' => 4,
                    '5 étoiles' => 5,
                ],
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('sort', ChoiceType::class, [
                'label' => 'Trier par',
                'choices' => [
                    'nom' => SearchData::SORT_TYPE_NAME,
                    'distance' => SearchData::SORT_TYPE_LOCATION,
                    'avis' => SearchData::SORT_TYPE_REVIEW,
                    'populaire' => SearchData::SORT_TYPE_POPULAR,
                ],
            ])
            ->add('query', HiddenType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method' => 'GET',
            'csrf_protection' => false,
            'allow_extra_fields' => true
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}