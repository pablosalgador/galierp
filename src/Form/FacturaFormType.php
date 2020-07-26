<?php

namespace App\Form;

use App\Entity\Factura;
use App\Entity\OportunidadVenta;
use App\Entity\Cliente;
use App\Entity\Empresa;
use App\Entity\Usuario;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FacturaFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('serie', TextType::class)
            ->add('numero_factura', IntegerType::class)
            ->add('fecha_emision', DateType::class, ['widget'=>'single_text'])
            ->add('irpf',  NumberType::class, ['required'=>false])
            ->add('oportunidadVenta', EntityType::class, [
              'class' => OportunidadVenta::class,
              'choice_label' => 'nombre',
              'placeholder' => '-',
              'required' => false,
              'disabled'=>$options['oportunidad_disabled']
              //'choices' => $options['oportunidades']
            ])
            ->add('cliente', EntityType::class, [
              'class' => Cliente::class,
              'choice_label' => 'nombre_comercial',
              'disabled'=>$options['cliente_disabled']
            ])
            ->add('empresa', EntityType::class, [
              'class' => Empresa::class,
              'choice_label' => 'nombrecomercial',
              'disabled'=>true
            ])
            ->add('lineas', CollectionType::class, [
              'entry_type' => LineaFacturaFormType::class,
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
            'data_class' => Factura::class,
            'oportunidades' => [],
            'oportunidad_disabled' => false,
            'cliente_disabled' =>false
        ]);
    }
}
