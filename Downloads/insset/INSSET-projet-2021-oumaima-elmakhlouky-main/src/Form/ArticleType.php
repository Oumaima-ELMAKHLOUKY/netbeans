<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Button;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre',TextType::class,[
                'attr' => [
                    'placeholder' => 'Ceci est un titre.'
                ]
            ])
            ->add('content',TextareaType::class,[
                'attr' => [
                    'placeholder' => 'Ceci est le corps.',
                    'cols' => '50',
                    'rows' => '10'
                ]
            ])
            ->add('imageADDR',TextType::class,[
                'attr' => [
                    'placeholder' => 'Glisser l\'image ici'
                ]
            ])
            ->add('publier')
            ->add('Save', SubmitType::class, [
                'attr' => ['class' => 'save btn btn-secondary'],
            ])
            //->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
