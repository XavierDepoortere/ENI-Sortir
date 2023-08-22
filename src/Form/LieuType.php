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
            ->add('nom', EntityType::class, [
                'class' => Lieu::class,
                'choice_label' => 'nom'
            ])
            ->add('rue', TextType::class, [
                'required' => false, 
            ])
            ->add('latitude', NumberType::class)
            ->add('longitude', NumberType::class)
            ->add('ville', VilleType::class);
        
       
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $lieu = $event->getData();
            $form = $event->getForm();

            if ($lieu instanceof Lieu) {
                $rue = $lieu->getRue();
                $form->add('rue', TextType::class, [
                    'required' => false, 
                    'data' => $rue,
                ]);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lieu::class,
        ]);
    }
}
