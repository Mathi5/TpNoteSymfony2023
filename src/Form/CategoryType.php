<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, [
                'label' => 'Titre :',
                'attr' => [
                    'placeholder' => 'Entrez un titre',
                    'class' => 'form-control',
                ]
            ])
            ->add('enable', null, [
                'label' => 'Actif : ',
                'attr' => [
                    'placeholder' => 'Actif',
                    'class' => 'form-check-input m-2',
                ]
            ])
            ->add('color', ColorType::class, [
                'label' => 'Couleur : ',
                'attr' => [
                    'placeholder' => 'Choisissez une couleur',
                    'class' => 'm-2',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
