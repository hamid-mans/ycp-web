<?php

namespace App\Form;

use App\Entity\Customers;
use App\Entity\Database;
use App\Entity\TypeDatabase;
use App\Repository\CustomersRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DatabaseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => false,
                'required' => false
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
                    'class' => 'ui search dropdown',
                ],
                'query_builder' => function (CustomersRepository $repository) {
                return $repository->createQueryBuilder('customer')
                    ->orderBy('customer.name', 'ASC');
                }
            ])
            ->add('type', EntityType::class, [
                'label' => false,
                'class' => TypeDatabase::class,
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'ui dropdown',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => $options['database_label'],
                'attr' => [
                    'class' => $options['database_class'],
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Database::class,
            'database_label' => 'Ajouter',
            'database_class' => 'ui gray button',
        ]);
    }
}
