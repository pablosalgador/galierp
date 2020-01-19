<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\TextType;
/*use Symfony\Component\Form\Extension\Core\Type\NumberType;*/
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Entity\ColumnaKanban;

class ColumnaKanbanType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class)
            ->add('descripcion', TextType::class)
            ->add('guardar', SubmitType::class, ['label'=> $options['label_guardar']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ColumnaKanban::class,
            'label_guardar' => 'Guardar'
        ]);
    }
}
