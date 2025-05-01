<?php

namespace App\Form;

use App\Entity\Customers;
use App\Entity\RemoteControlTool;
use App\Entity\Server;
use App\Entity\TypeServer;
use App\Repository\CustomersRepository;
use App\Repository\RemoteControlToolRepository;
use App\Repository\TypeServerRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateServerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false
            ])
            ->add('localIp', TextType::class, [
                'label' => false,
                'required' => false
            ])
            ->add('publicIp', TextType::class, [
                'label' => false,
                'required' => false
            ])
            ->add('login', TextType::class, [
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
                'query_builder' => function (CustomersRepository  $er) {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.name', 'ASC');
                }
            ])
            ->add('type', EntityType::class, [
                'label' => false,
                'class' => TypeServer::class,
                'choice_label' => 'name',
                'required' => false,
                'attr' => [
                    'class' => 'ui search dropdown',
                ],
                'query_builder' => function (TypeServerRepository $er) {
                    return $er->createQueryBuilder('t')
                        ->orderBy('t.name', 'ASC');
                }
            ])
            ->add('remoteControl', EntityType::class, [
                'label' => false,
                'class' => RemoteControlTool::class,
                'choice_label' => 'name',
                'required' => false,
                'attr' => [
                    'class' => 'ui search dropdown',
                ],
                'query_builder' => function (RemoteControlToolRepository $er) {
                    return $er->createQueryBuilder('t')
                    ->orderBy('t.name', 'ASC');
                }
            ])
            ->add('submit', SubmitType::class, [
                'label' => '<i class="plus icon"></i>Ajouter',
                'attr' => [
                    'class' => 'ui icon labeled green button'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Server::class,
        ]);
    }
}
