<?php

namespace App\Form;

use App\Entity\Articles;
use App\Entity\Categories;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ArticlesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('categorie',EntityType::class,[
                'label' => 'Catégorie',
                'class' => Categories::class,
                'choice_label' => 'name',
                'placeholder' => 'Séléctionnez une catégorie'
            ])
            ->add('name',TextType::class,[
                'label' => 'Nom'
            ])
            ->add('price',IntegerType::class,[
                'label' => 'Prix'
            ])
            ->add('imageFile',FileType::class,[
                'label' => false
            ])
            // ->add('createdAt')
            // ->add('commandes')
            // ->add('likes')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Articles::class,
        ]);
    }
}
