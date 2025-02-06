<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('email', EmailType::class)
          ->add('password', RepeatedType::class, [
              'type' => PasswordType::class,
              'invalid_message' => 'Пароли не совпадают',
              'options' => ['attr' => ['class' => 'password-field']],
              'required' => true,
              'first_options'  => ['label' => 'Пароль'],
              'second_options' => ['label' => 'Повторите пароль'],
              'constraints' => [
                  new NotBlank([
                      'message' => 'Пожалуйста, введите пароль',
                  ]),
                  new Length([
                      'min' => 6,
                      'minMessage' => 'Пароль должен быть не менее 6 символов',
                      // max length allowed by Symfony for security reasons
                      'max' => 4096,
                  ]),
              ],
          ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
