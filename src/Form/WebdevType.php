<?php

namespace App\Form;

use App\Entity\Customers;
use App\Entity\Webdev;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WebdevType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('serialNumber', TextType::class, [
                'label' => false,
                'required' => false
            ])
            ->add('activationKey', TextType::class, [
                'label' => false,
                'required' => false
            ])
            ->add('echeanceDate', DateType::class, [
                'label' => false,
                'widget' => 'single_text'
            ])
            ->add('customer', EntityType::class, [
                'label' => false,
                'class' => Customers::class,
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
            'data_class' => Webdev::class,
            'submit_label' => 'Envoyer',
            'submit_class' => 'ui button'
        ]);
    }
}
