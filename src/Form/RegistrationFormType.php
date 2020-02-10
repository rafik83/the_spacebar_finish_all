<?php

namespace App\Form;

use App\Entity\User;
use App\Form\Model\UserRegistrationFormModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
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
            ->add('email',EmailType::class)
//            ->add('password')
                //dont't use password, avoid EVER setting that on a
                // field that might be persisted

            ->add('plainPassword',PasswordType::class)
//            ->add('plainPassword',PasswordType::class,[
////                'mapped'=> false,
//                'constraints' => [
//                    new NotBlank([
//                        'message'=> 'Choose a Password!'
//                    ]),
//                    new Length([
//                        'min'=> 5,
//                        'minMessage' => 'Come on, you can think of a password longer than that!'
//                    ])
//                ]
//            ])
            ->add('agreeTerms',CheckboxType::class)
//            ->add('agreeTerms',CheckboxType::class,[
////                'mapped'=> false,
//                'constraints' => [
//                new IsTrue([
//                    'message'=> 'vous devez cocher cette case'
//                ])
//
//                ]
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
//            'data_class' => User::class,
               'data_class'=> UserRegistrationFormModel::class,
        ]);
    }
}
