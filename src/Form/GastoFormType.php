<?php

namespace App\Form;

use App\Entity\Gasto;
use App\Entity\Empresa;
use App\Entity\Proveedor;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;



class GastoFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fecha', DateType::class)
            ->add('descripcion', TextType::class)
            ->add('base', NumberType::class)
            ->add('iva', NumberType::class)
            ->add('justificante', TextType::class, ['required' => false])
            ->add('empresa', EntityType::class, [
              'class' => Empresa::class,
              'choice_label' => 'nombre_comercial',
              'placeholder' => '- Seleccionar Empresa -',
              'required' => true,
              'disabled' => $options['empresa_disabled']
            ])
            ->add('proveedor', EntityType::class, [
              'class' => Proveedor::class,
              'choice_label' => 'nombre_comercial',
              'placeholder' => ' - Seleccionar Proveedor -',
              'required'=>true,
              'disabled'=>$options['proveedor_disabled']
            ])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Gasto::class,
            'proveedor_disabled' => false,
            'empresa_disabled' => false
        ]);
    }
}
