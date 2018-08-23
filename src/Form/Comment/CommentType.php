<?php
// src/Form/Comment/CommentType.php

namespace App\Form\Comment;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder 
      ->add('content',            TextareaType::class, array(
        'label' => ' ',
        'attr' => array(
          'placeholder' => 'Ecrire un commentaire ...',
        )
      ))
      ->add('save',            SubmitType::class, array(
        'label' => 'Ajouter',
      ))
    ;
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
    	'data_class' => 'App\Entity\Comment',
	));
  }
}