<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Participant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class ParticipantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('pseudo')
        ->add('nom')
        ->add('prenom')
        ->add('telephone')
        ->add('email')
        ->add('campus', EntityType::class, [
            'class' => Campus::class, 
            'choice_label' => 'nom'
        ])
        ->add('motPasse', RepeatedType::class, [
            'type' => PasswordType::class,
            'required' => false,
            'mapped' => false,
            
            'options' => ['attr' => ['class' => 'password-field']],
            'first_options'  => ['label' => 'Password'],
            'second_options' => ['label' => 'Repeat Password'],
        ]);
}

public function configureOptions(OptionsResolver $resolver): void
{
    $resolver->setDefaults([
        'data_class' => Participant::class,
    ]);
}
}