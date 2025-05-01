<?php

namespace App\Form;

use App\Entity\Customers;
use App\Entity\Prestataires;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PrestatairesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
            ])
            ->add('cop', TextType::class, [
                'label' => false,
                'required' => false
            ])
            ->add('city', TextType::class, [
                'label' => false,
                'required' => false
            ])
            ->add('phone', TextType::class, [
                'label' => false,
                'required' => false
            ])
            ->add('contact_name', TextType::class, [
                'label' => false,
                'required' => false
            ])
            ->add('contact_phone', TextType::class, [
                'label' => false,
                'required' => false
            ])
            ->add('customer', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Customers::class,
                'choice_label' => 'name',
                'multiple' => true,
                'attr' => [
                    'class' => 'ui search dropdown'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => $options['submit_label'],
                'attr' => [
                    'class' => $options['submit_class'],
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Prestataires::class,
            'submit_label' => "Valider",
            'submit_class' => "ui gray button",
        ]);
    }
}
