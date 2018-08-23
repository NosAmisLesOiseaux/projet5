<?php
// src/Form/Capture/NaturalistCaptureType.php

namespace App\Form\Capture;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Form\Capture\ParticularCaptureType;

class NaturalistCaptureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('status',
                ChoiceType::class,
                array(
                    'label' => 'Enregistrer l\'observation :',
                    'choices' => array(
                        'Brouillon' => 'draft',
                        'Publié' => 'published',
                        ),
                    'expanded' => true,
                )
            )
        ;
    }

    public function getParent()
    {
        return ParticularCaptureType::class;
    }
}
