<?php

namespace App\Form;

use App\Entity\Ingreso;
use App\Entity\Empresa;
use App\Entity\Factura;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IngresoFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fecha', DateType::class)
            ->add('descripcion',TextareaType::class)
            ->add('cantidad', NumberType::class)
            ->add('empresa', EntityType::class, [
              'class' => Empresa::class,
              'choice_label' => 'nombre_comercial',
              'placeholder' => '- Seleccionar Empresa -',
              'required' => true,
              'disabled'=>true
            ])
            ->add('factura',EntityType::class, [
              'class' => Factura::class,
              'choice_label' => function ($factura){
                return $factura->getSerie() . '/' . $factura->getNumeroFactura();
              },
              'placeholder' => '- Seleccionar Factura -',
              'required' => false,
              'choices' => $options['facturas'],
              'disabled' => $options['factura_disabled']
            ])
            ->add('save',SubmitType::class,[
              'label'=>'Guardar'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ingreso::class,
            'facturas' => [],
            'factura_disabled' => false,
        ]);
    }
}
