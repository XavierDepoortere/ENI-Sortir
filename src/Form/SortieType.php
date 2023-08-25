<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Ville;
use App\Entity\Sortie;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;



class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $userCampus = $options['userCampus'];
        $builder
        
            ->add('nom')
            ->add('dateHeureDebut', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('duree', IntegerType::class)
            ->add('dateLimiteInscription', DateType::class, [
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('nbInscriptionsMax', IntegerType::class)
            ->add('infosSortie', TextareaType::class)
            ->add('siteOrganisateur', TextType::class, [
                'label' => 'Campus',
                'attr' => ['class' => 'px-2 py-1 border rounded focus:outline-none focus:border-blue-500'],
                'data' => $userCampus->getNom(),
                'required' => true, 
                'disabled'=> 'disabled',
            ])

            ->add('lieu', EntityType::class, [
               
                'class'=>Lieu::class,
                'choice_label'=>'nom',
                'placeholder'=>'Sélectionner un lieu',
                
                
            ])
            ->add('rue', TextType::class, [
                'mapped'=>false, 
            ])
            ->add('codePostal', TextType::class, [
                'mapped'=>false, 
            ])
            ->add('latitude', TextType::class, [
                'mapped'=>false, 
            ])
            ->add('longitude', TextType::class, [
                'mapped'=>false, 
            ])
            
            ->add('ville', EntityType::class, [
                'mapped'=>false, 
                'class'=>Ville::class,
                'choice_label'=>'nom',
                'placeholder'=>'Sélectionner une ville',
                'choice_value'=> 'nom',
                
            ])
            
            ->add('save', SubmitType::class, [
                'label'=> 'Enregistrer',
            ])
            ->add('post', SubmitType::class, [
                'label'=> 'Publier la sortie',
            ])
            ->add('etats', HiddenType::class)
            ;
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
            'userCampus' => null,
        ]);
    }
}
