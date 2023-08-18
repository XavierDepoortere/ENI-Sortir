<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Participant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class ParticipantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $participant = $options['data'];

        $builder
        ->add('pseudo')
        ->add('nom')
        ->add('prenom')
        ->add('telephone')
        ->add('email')
        ->add('campus', TextType::class, [
            'label' => 'Campus',
            'attr' => ['class' => 'px-2 py-1 border rounded focus:outline-none focus:border-blue-500'],
            'data' => $options['data']->getCampus()->getNom(),
            'required' => true, 
            'mapped' => false,
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