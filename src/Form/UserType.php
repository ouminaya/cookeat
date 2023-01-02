<?php

namespace App\Form;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;


class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'attr' => [
                    'class' => 'form-cotrol mt-4',
                    'minlenght' => '2',
                    'maxlenght' => '50',
                ],

                'label' => 'Prénom',
                'label_attr' => [
                    'class' => 'from-label mt-4'
                ],
                'constraints' => [
                    new NotBlank(),
                    new length(['min' => 2, 'max' => 50])
                ]

            ])
            ->add('lastname', TextType::class, [
                'attr' => [
                    'class' => 'form-cotrol mt-4',
                    'minlenght' => '2',
                    'maxlenght' => '50',
                ],
                'label' => 'Nom',
                'label_attr' => [
                    'class' => 'from-label mt-4'
                ],
                'constraints' => [
                    new length(['min' => 2, 'max' => 50])
                ],
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success mt-4'
                ]

            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
