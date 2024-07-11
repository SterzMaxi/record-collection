<?php

namespace App\Form\Type;

use Symfony\Component\Form\Extensions\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, array(
                'attr' => array(
                    'placeholder' =>'Record Titel eingeben'
                )
            ))
            ->add('save', SubmitType::class)
            ;
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Record::class,
            'csrf_protection' => false,
        ]);
    }
}