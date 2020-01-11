<?php

namespace App\Form;

use App\Entity\Usuario;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UsuarioFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, array('required'=> true))
            ->add('nombre', TextType::class, array('required'=> true))
            ->add('apellidos', TextType::class, array('required'=> true))
            ->add('plainPassword', PasswordType::class,[
                'mapped'=>false,
                'required'=>true,
                'label'=>'Contraseña',
                'constraints'=>[new NotBlank(),new Length(['min'=>6,'max'=>120])]
              ] )
            //->add('password')
            ->add('save', SubmitType::class, array('label' => 'Guardar'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Usuario::class,
        ]);
    }
}
