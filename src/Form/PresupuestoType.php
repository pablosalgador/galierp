<?php

namespace App\Form;

use App\Entity\Presupuesto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Entity\Cliente;
use App\Entity\LineaPresupuesto;
use App\Entity\OportunidadVenta;


class PresupuestoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('numero_presupuesto', TextType::class)
          ->add('fecha_emision', DateType::class, ['widget'=>'single_text'])
          ->add('dias_validez', IntegerType::class)
          ->add('cliente', EntityType::class, [
            'class' => Cliente::class,
            'choice_label' => 'nombre_comercial'
          ])
          ->add('oportunidadVenta', EntityType::class, [
            'class' => OportunidadVenta::class,
            'choice_label' => 'nombre',
            'placeholder' => '-',
            'required' => false,
            'choices' => $options['oportunidades']
          ])
          ->add('lineas', CollectionType::class, [
            'entry_type' => LineaPresupuestoType::class,
            'entry_options' => ['label'=>false],
            'allow_add' => true,
            'allow_delete' => true,
            'label'=>false
          ])
          ->add('guardar', SubmitType::class, ['label'=>'Guardar'])
        ;


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Presupuesto::class,
            'oportunidades' => []
        ]);
    }
}
