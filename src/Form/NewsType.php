<?php

namespace App\Form;

use App\Entity\News;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $label = "Add new Post";

        if (isset($options['attr']) && isset($options['attr']['data'])){

            $type = $options['attr']['data'];
            switch ($type) {
                case 'update':
                    $label = 'Update Post';
                break;
                default:
                    $label = "Add new Post";
            }

        }
        $builder
            ->add('title', TextType::class , ['attr'  => ['class' => 'form-control' ] ])
            ->add('post', TextareaType::class , ['attr'  => ['class' => 'form-control' ,  'rows' => '4' ] ])
            ->add('save', SubmitType::class , ['label' => $label , 'attr'  => ['class' => 'btn btn-primary btn-lg' ] ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => News::class,
        ]);
    }
}
