<?php

namespace App\Form;

use App\Entity\EventoTipo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class EventoTipoColType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('fechaInicio', DateTimeType::class, [
                'label' => 'eventoTipo.fechaInicio',
            ])
            ->add('fechaFin', DateTimeType::class, [
                'label' => 'eventoTipo.fechaFin',
            ])
            ->add('activo')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => EventoTipo::class,
        ]);
    }
}
