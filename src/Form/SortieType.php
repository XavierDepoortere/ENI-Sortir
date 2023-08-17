<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sortie;

use phpDocumentor\Reflection\Types\True_;
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
use function Sodium\add;


class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('dateHeureDebut', DateTimeType::class)
            ->add('duree', IntegerType::class)
            ->add('dateLimiteInscription', DateType::class)
            ->add('nbInscriptionsMax', IntegerType::class)
            ->add('infosSortie', TextareaType::class)
            ->add('siteOrganisateur', TextType::class)
            ->add('lieux', EntityType::class, [
                'class' => Lieu::class,
                'choice_label' => 'nom'
            ])


        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
