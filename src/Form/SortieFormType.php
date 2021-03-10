<?php

namespace App\Form;

use App\Entity\Sortie;
use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('datedebut', DateTimeType::class, [
                'widget'    => 'choice',
                'years'     => range(date('Y'), date('Y')+100),
                'months'    => range(date('m'), 12),
                'days'      => range(date('d'), 31)
            ])
            ->add('duree', IntegerType::class, [
                'label'     => 'Durée de la sortie',
                'attr'      => ['min' => 1, 'max'   => 10]
            ])
            ->add('limitInscription', DateTimeType::class, [
                'label'     => 'Date limite d\'inscription',
                'widget'    => 'choice',
                'years'     => range(date('Y'), date('Y')+100),
                'months'    => range(date('m'), 12),
                'days'      => range(date('d'), 31)
            ])
            ->add('nbInscriptionMax',IntegerType::class, [
                'attr' => ['min' => 1, 'max'   => 15]
            ])
            ->add('infoSortie', textareatype::class, [
                'label' => 'information complémentaire'
            ])
//            ->add('etatSortie')
//            ->add('Etat')
            ->add('lieu')
            ->add('enregistrer', SubmitType::class, ['label' => 'enregistrer'])
            ->add('publier', SubmitType::class, ['label' => 'publier'])
            ->add('supprimer', SubmitType::class, ['label' => 'supprimer'])
            ->add('annuler', SubmitType::class, ['label' => 'annuler'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
            'csrf_protection' => false,
        ]);
    }
}
