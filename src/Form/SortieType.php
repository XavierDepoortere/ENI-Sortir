<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sortie;

use App\Entity\Ville;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;



class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $userCampus = $options['userCampus'];
        $builder
            ->add('nom')
            ->add('dateHeureDebut', DateTimeType::class)
            ->add('duree', IntegerType::class)
            ->add('dateLimiteInscription', DateType::class)
            ->add('nbInscriptionsMax', IntegerType::class)
            ->add('infosSortie', TextareaType::class)
            ->add('siteOrganisateur', TextType::class, [
                'label' => 'Campus',
                'attr' => ['class' => 'px-2 py-1 border rounded focus:outline-none focus:border-blue-500'],
                'data' => $userCampus->getNom(),
                'required' => true, 
                'disabled'=> 'disabled',
                
            ])
            ->add('lieux', LieuType::class);
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
            'userCampus' => null,
        ]);
    }
}
