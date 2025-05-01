<?php

namespace App\Form;

use App\Entity\Database;
use App\Entity\Server;
use App\Entity\Software;
use App\Entity\User;
use App\Repository\DatabaseRepository;
use App\Repository\ServerRepository;
use App\Repository\SoftwareRepository;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationFormType extends AbstractType
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false
            ])
            ->add('email', TextType::class, [
                'label' => false
            ])
            ->add('password', PasswordType::class, [
                'mapped' => false,
                'label' => false,
                'required' => false
            ])
            ->add('roles', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    'Utilisateur' => 'ROLE_USER',
                    'Administrateur' => 'ROLE_ADMIN',
                ],
                'expanded' => false,
                'multiple' => true,
                'attr' => [
                    'class' => 'ui fluid multiple dropdown',
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => $options['submit_label'],
                'attr' => [
                    'class' => $options['submit_class'],
                ]
            ])
            ->add('serversForbidden', EntityType::class, [
                'class' => Server::class,
                'query_builder' => function (ServerRepository $er) {
                return $er->createQueryBuilder('s')
                    ->leftJoin('s.customer', 'c')
                    ->orderBy('c.name', 'ASC');
                },
                'choice_label' => function(Server $server) {
                    return $server->getCustomer()->getName() . " --> " . $server->getName();
                },
                'multiple' => true,
                'expanded' => false,
                'required' => false,
                'label' => false,
                'attr' => [
                    'class' => 'ui fluid search multiple dropdown'
                ]
            ])
            ->add('softwaresForbidden', EntityType::class, [
                'class' => Software::class,
                'query_builder' => function (SoftwareRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->leftJoin('s.customer', 'c')
                        ->orderBy('c.name', 'ASC');
                },
                'choice_label' => function(Software $software) {
                    return $software->getCustomer()->getName() . ' --> ' . $software->getType()->getName();
                },
                'multiple' => true,
                'expanded' => false,
                'required' => false,
                'label' => false,
                'attr' => [
                    'class' => 'ui fluid search multiple dropdown'
                ]
            ])
            ->add('databasesForbidden', EntityType::class, [
                'class' => Database::class,
                'query_builder' => function (DatabaseRepository $er) {
                    return $er->createQueryBuilder('d')
                        ->leftJoin('d.customer', 'c')
                        ->orderBy('c.name', 'ASC');
                },
                'choice_label' => function(Database $database) {
                    return $database->getCustomer()->getName() . ' --> ' . $database->getType()->getName();
                },
                'multiple' => true,
                'expanded' => false,
                'required' => false,
                'label' => false,
                'attr' => [
                    'class' => 'ui fluid search multiple dropdown'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'submit_label' => 'Valider',
            'submit_class' => 'ui gray button'
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'registration_form';
    }
}
