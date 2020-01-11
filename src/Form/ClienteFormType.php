<?php

namespace App\Form;

use App\Entity\Cliente;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClienteFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nif', TextType::class, array('required'=> true))
            ->add('razon_social', TextType::class, array('required'=>true))
            ->add('nombre_comercial', TextType::class, array('required'=>true))
            ->add('direccion', TextType::class, array('required'=>true))
            ->add('codigo_postal', TextType::class, array('required'=>true))
            ->add('localidad', TextType::class, array('required'=>true))
            ->add('provincia', TextType::class, array('required'=>true))
            ->add('pais', TextType::class, array('required'=>true))
            ->add('persona_contacto', TextType::class, array('required'=>false))
            ->add('telefono', IntegerType ::class, array('required'=>true))
            ->add('email', TextType::class, array('required'=>true))
            ->add('web', TextType::class, array('required'=>false))
            ->add('comentarios', TextareaType::class, array('required'=>false))
            ->add('save', SubmitType::class, array('label' => 'Guardar'))
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Cliente::class,
        ]);
    }
}
