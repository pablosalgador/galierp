<?php

namespace App\Form;

use App\Entity\LineaFactura;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LineaFacturaFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('cantidad', IntegerType::class, array('required'=>true))
          ->add('concepto', TextType::class, array('required'=>true))
          ->add('precio', NumberType::class, array('required'=>true))
          ->add('descuento', NumberType::class, array('required'=>false))
          ->add('iva', NumberType::class, array('required'=>true))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LineaFactura::class,
        ]);
    }
}
