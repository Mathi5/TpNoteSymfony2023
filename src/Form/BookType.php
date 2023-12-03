<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null , [
                'label' => 'Titre',
                'attr' => [
                    'placeholder' => 'Entrez un titre',
                    'class' => 'form-control'
                ]
            ])
            ->add('publishedDate', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de publication',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('summary', TextareaType   ::class, [
                'required' => false,
                'label' => 'Résumé',
                'attr' => [
                    'placeholder' => 'Entrez un résumé',
                    'class' => 'form-control'
                ]
            ])
            ->add('author', EntityType::class, [
                'class' => Author::class,
                'choice_label' => 'fullName',
                'multiple' => false,
                'label' => 'Auteur',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'title',
                'multiple' => true,
                'label' => 'Catégorie',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
