<?php
/**
 * Created by PhpStorm.
 * User: seymos
 * Date: 04/08/18
 * Time: 21:35
 */

namespace App\Form\Password;


use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReinitialisationPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'password',
                RepeatedType::class,
                array(
                    'type' => PasswordType::class,
                    'required' => true,
                    'invalid_message' => 'Les mots de passe doivent correspondre.',
                    'first_options' => array('label' => "Entrez le nouveau mot de passe"),
                    'second_options' => array('label' => "Confirmez le nouveau mot de passe")
                )
            )
            ->add(
                'token',
                HiddenType::class
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class
        ]);
    }
}
