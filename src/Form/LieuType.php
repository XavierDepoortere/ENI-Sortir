<?php

namespace App\Form;

use App\Entity\Lieu;


use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class LieuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class)
            ->add('rue', TextType::class, [
                'required' => true, 
                'disabled'=> 'disabled',
                'mapped' => false,
            ])
            ->add('latitude', NumberType::class,[
                'disabled'=> 'disabled',
                
            ])
            ->add('longitude', NumberType::class,[
                
            ])
            ->add('ville', NumberType::class,[
                
            ]);
        
       
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lieu::class,
        ]);
    }
}
