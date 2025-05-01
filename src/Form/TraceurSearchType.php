<?php

namespace App\Form;

use App\Entity\Traceur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TraceurSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', ChoiceType::class, [
                'choices' => [
                    "DELETE" => "DELETE",
                    "CREATE" => "CREATE",
                    "UPDATE" => "UPDATE",
                    "READ" => "READ",
                ],
                'label' => false,
                'required' => false,
                'attr' => [
                    'class' => 'ui dropdown'
                ]
            ])
            ->add('data', ChoiceType::class, [
                'choices' => [
                    'Fichiers' => [
                        '--------- Fichiers ---------' => '--------- Fichiers ---------',
                        'Client' => 'Client',
                        'Prestataires' => 'Prestataires',
                        'Serveurs' => 'Serveurs',
                        'Logiciels' => 'Logiciels',
                        'Base de donnée' => 'Base de donnée',
                    ],
                    'Paramètres' => [
                        '--------- Paramètres ---------' => '--------- Paramètres ---------',
                        'Utilisateur' => 'Utilisateur',
                        'Type Serveur' => 'Type Serveur',
                        'Outil prise en main' => 'Outil prise en main',
                        'Type base de données' => 'Type base de données',
                        'Type de logiciel' => 'Type de logiciel',
                    ],
                    'Autres' => [
                        '--------- Autres ---------' => '--------- Autres ---------',
                        'Notes' => 'Notes',
                    ]
                ],
                'label' => false,
                'required' => false,
                'attr' => [
                    'class' => 'ui dropdown'
                ]
            ])
            ->add('dataId', NumberType::class, [
                'label' => false,
                'required' => false
            ])
            ->add('username', TextType::class, [
                'label' => false,
                'required' => false
            ])
            /*->add('datetime', DateTimeType::class, [
                'label' => false,
                'widget' => 'single_text',
                'required' => false,
            ])*/
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
            'submit_label' => null,
            'submit_class' => null,
        ]);
    }
}
