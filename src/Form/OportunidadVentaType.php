<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Entity\OportunidadVenta;
use App\Entity\Usuario;
use App\Entity\Cliente;
use App\Entity\ColumnaKanban;

class OportunidadVentaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('nombre', TextType::class)
        ->add('descripcion', TextAreaType::class)
        ->add('responsable', EntityType::class, [
          'class' => Usuario::class,
          'choice_label' => 'email',
        ])
        ->add('cliente', EntityType::class,[
          'class' => Cliente::class,
          'choice_label' => 'nombre_comercial',
          'placeholder'=>'-',
          'required'=>false
        ])
        ->add('ganada', CheckBoxType::class,[
          'required'=>false
        ])
        ->add('perdida', CheckBoxType::class,[
          'required'=>false
        ])
        ->add('motivoperdida',  TextAreaType::class,[
          'label'=>'Motivo Pérdida',
          'required'=>false
        ])
        ->add('columna_kanban', EntityType::class, [
          'class' => ColumnaKanban::class,
          'choice_label' => 'nombre',
          'placeholder'=>'-',
          'required'=>false])
          ->add('ingreso_estimado', NumberType::class, ['scale'=> 2])
          ->add('porcentaje_exito_estimado', RangeType::class, ['attr'=>['min'=>0,'max'=>1,'step'=>0.05]])
          ->add('guardar', SubmitType::class, ['label'=> $options['label_guardar']])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OportunidadVenta::class,
            'label_guardar' => 'Guardar'
        ]);
    }
}
