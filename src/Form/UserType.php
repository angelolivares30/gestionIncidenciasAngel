<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email', EmailType::class)
        ->add('password', PasswordType::class, [
            'constraints' => [
                new Length([
                    'min' => 5,
                    'minMessage' => 'La contraseña debe tener al menos {{ limit }} caracteres.',
                    // También puedes definir un mensaje personalizado para cuando la longitud es menor que 5
                    // 'maxMessage' => 'La contraseña no puede tener más de {{ limit }} caracteres.'
                ]),
            ],
        ])
        ->add('nombre', TextType::class)
        ->add('apellidos', TextType::class)
        ->add('telefono', TelType::class)
        ->add('foto', FileType::class, [
            'label'=> 'foto',
            'required' => false,
        ])
        
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
