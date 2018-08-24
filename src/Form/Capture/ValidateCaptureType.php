<?php

namespace App\Form\Capture;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ValidateCaptureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'naturalist_comment',
                TextareaType::class,
                array(
                    'label' => 'Partie Naturaliste',
                    'required'   => false,
                    'attr' => array(
                        'placeholder' => 'Ecrire un commentaire ...',
                    )
                )
            )
        ;
    }

    public function getParent()
    {
        return ParticularCaptureType::class;
    }
}
