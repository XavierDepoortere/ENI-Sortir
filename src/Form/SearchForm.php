<?php

namespace App\Form;

use App\Entity\Etat;
use App\Entity\Campus;
use App\Entity\Sortie;
use App\Data\SearchData;
use App\Entity\Participant;
use Symfony\Component\Form\AbstractType;
use App\Repository\ParticipantRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;


class SearchForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options,): void
    {
        $builder
        ->add('q', TextType::class, [
            'label' => false,
            'required' => false,
            'attr' => [
                'placeholder' => 'rechercher',]
        ])
        ->add('siteOrganisateur', EntityType::class, [  
            'class' => Campus::class,  
            'choice_label' => 'nom',
            'label' => false,
            'required' => true, 
            'expanded' => false,
            'multiple'=>false,
            'data' => $options['data']->getCampus(),
        ])
        ->add('dateMin', DateType::class, [
            'label' => 'Date minimale',
            'widget' => 'single_text',
            'required' => false,
            'placeholder' => 'Date de début'
        ])
        ->add('dateMax', DateType::class, [
            'label' => 'Date maximale',
            'widget' => 'single_text',
            'required' => false,
        ])
        ->add('inscrit', CheckboxType::class, [
            'label' => 'Sorties auxquelles je suis inscrit/e',
            'required' => false,
            'mapped' => true,
        ])
        ->add('organisateur', CheckboxType::class, [
            'label' => 'Sorties dont je suis organisateur',
            'required' => false,
            'mapped' => true,
            
        ])
        ->add('nonInscrit', CheckboxType::class, [
            'label' => 'Inclure les sorties auxquelles je ne suis pas inscrit',
            'required' => false,
            
        ])
        ->add('sortiePassee', CheckboxType::class, [
            'label' => 'Sorties passées',
            'required' => false,
        ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';

}
}