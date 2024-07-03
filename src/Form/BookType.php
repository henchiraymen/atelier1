<?php

namespace App\Form;

use App\Entity\Book;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ref')
            ->add('title')
            ->add('category',ChoiceType::class,
            ['choices'=>[
                'Science Fiction'=>'SC',
                'Mystery'=>'M',
                'AutoBiography'=>'B']])
            ->add('publicationDate')
            ->add('author',EntityType::class,
            ['class'=>'App\Entity\Author',
            'choice_label'=>'username',
            #'expanded'=>true
            #'multiple'=>true
            ])
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
            ''
        ]);
    }
}
