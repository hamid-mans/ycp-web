<?php

namespace App\Form;

use App\Entity\Customers;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateCustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
            ])
            ->add('phone', TextType::class, [
                'label' => false,
                'required' => false
            ])
            ->add('cop', TextType::class, [
                'label' => false,
                'required' => false
            ])
            ->add('city', TextType::class, [
                'label' => false,
                'required' => false
            ])
            ->add('submit', SubmitType::class, [
                'label' => '<i class="plus icon"></i>Ajouter le client',
                'attr' => [
                    'class' => 'ui green labeled icon button'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Customers::class,
        ]);
    }
}
