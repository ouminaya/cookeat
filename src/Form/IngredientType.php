<?php

namespace App\Form;

use App\Entity\Ingredients;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class IngredientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,[
                'attr' => [
                    'class'=> 'form-control',
                    'minlength' => '2', 
                    'maxlength' => '50'             
                ],
                  'label' => 'Nom',
                  'label_attr' => [
                    'class' => 'form-label mt-4'
                  ],
                  'constraints' => [
                    new Assert\Length(['min' => 2, 'max' => 50 ]),
                    new Assert\NotBlank()
                  ]
            ])
            

            ->add('quantity' , NumberType::class,[
                'attr' => [
                'class'=> 'form-control',
                         
            ],
              'label' => 'Quantity',
              'label_attr' => [
                'class' => 'form-label mt-4'
                
              ],
              'constraints' => [
                new Assert\Positive(),
                
              ]
                
            ])
            ->add('submit', SubmitType::class,[
                'attr' => ['btn btn-success mt-4'
            ],
            'label'=> 'Créer mon ingrédient' 
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ingredients::class,
        ]);
    }
}
