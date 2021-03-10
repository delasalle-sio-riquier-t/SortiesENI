<?php

namespace App\Form;

use App\Entity\Sortie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('datedebut')
            ->add('duree')
            ->add('limitInscription')
            ->add('nbInscriptionMax')
            ->add('infoSortie')
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
        ]);
    }
}
