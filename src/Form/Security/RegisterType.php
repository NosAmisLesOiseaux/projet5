<?php
/**
 * Created by PhpStorm.
 * User: seymos
 * Date: 05/08/18
 * Time: 15:17
 */

namespace App\Form\Security;


use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'firstname',
                TextType::class
            )
            ->add(
                'lastname',
                TextType::class
            )
            ->add(
                'account_type',
                ChoiceType::class,
                array(
                    'choices' => array(
                        "Je suis un passionné" => 'particular',
                        "Je suis ornithologue" => 'naturalist'
                    )
                )
            )
            ->add(
                'email',
                EmailType::class
            )
            ->add(
                'password',
                RepeatedType::class,
                array(
                    'type' => PasswordType::class,
                    'first_options' => array(
                        'label' => "Mot de passe"
                    ),
                    'second_options' => array(
                        'label' => "Confirmer le mot de passe"
                    )
                )
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class
        ]);
    }
}
