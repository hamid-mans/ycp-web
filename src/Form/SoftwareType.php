<?php

namespace App\Form;

use App\Entity\Customers;
use App\Entity\Software;
use App\Entity\TypeSoftware;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SoftwareType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => false
            ])
            ->add('password', TextType::class, [
                'label' => false,
                'required' => false
            ])
            ->add('comment', TextareaType::class, [
                'label' => false,
                'required' => false
            ])
            ->add('customer', EntityType::class, [
                'label' => false,
                'class' => Customers::class,
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'ui search dropdown'
                ]
            ])
            ->add('type', EntityType::class, [
                'label' => false,
                'class' => TypeSoftware::class,
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'ui search dropdown'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => $options['submit_label'],
                'attr' => [
                    'class' => $options['submit_class']
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Software::class,
            'submit_label' => null,
            'submit_class' => null
        ]);
    }
}
