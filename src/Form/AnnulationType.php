<?php

namespace App\Form;

use App\Entity\Sortie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnnulationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', textType::class, [
                'disabled'  => true,
            ])
            ->add('datedebut', DateTimeType::class, [
                'widget'   => 'single_text',
                'disabled'  => true,
            ])
            ->add('site',textType::class, [
                'disabled'  => true,
            ])
            ->add('lieu',textType::class, [
                'disabled'  => true,
            ])
            ->add('motifAnnulation')
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
