<?php

namespace App\Form;

use App\Entity\Ville;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class VilleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom', EntityType::class, [
            'class' => Ville::class,
            'choice_label' => 'nom'
        ])

            ->add('codePostal', TextType::class, [
                'constraints' => [
                    new Length([
                        'min' => 5,
                        'max' => 5,
                        'minMessage' => 'Le code postal doit avoir exactement {{ limit }} caractères.',
                        'maxMessage' => 'Le code postal doit avoir exactement {{ limit }} caractères.',
                    ]),
                ],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ville::class,
        ]);
    }
}
