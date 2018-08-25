<?php
/**
 * Created by PhpStorm.
 * User: seymos
 * Date: 04/08/18
 * Time: 21:49
 */

namespace App\Form\Password;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'new_password',
                RepeatedType::class,
                array(
                    'type' => PasswordType::class,
                    'required' => true,
                    'first_options' => array(
                        'label' => "Entrez le nouveau mot de passe"
                    ),
                    'second_options' => array(
                        'label' => "Confirmez le nouveau mot de passe"
                    )
                )
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
    }
}
