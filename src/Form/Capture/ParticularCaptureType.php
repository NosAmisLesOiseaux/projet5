<?php

namespace App\Form\Capture;

use App\Entity\Bird;
use App\Entity\Capture;
use App\Repository\BirdRepository;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParticularCaptureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'bird',
                EntityType::class,
                array(
                    'class' => Bird::class,
                    'query_builder' => function (BirdRepository $er) {
                        return $er->createQueryBuilder('u')
                            ->orderBy('u.vernacularname', 'ASC')
                            ->addOrderBy('u.validname', 'ASC');
                        },
                'choice_label' => function (Bird $bird) {if (!empty($bird->getVernacularname())) { return $bird->getVernacularname() . ' - ' . $bird->getValidname();} else {return $bird->getValidname();}},
                'label' => ' ',
                'placeholder' => 'Sélectionner un oiseau',
                    )
            )
            ->add(
                'content',
                TextareaType::class,
                array(
                    'label' => 'Observation :'
                )
            )
            ->add(
                'latitude',
                NumberType::class,
                array(
                    'label' => 'Latitude'
                )
            )
            ->add(
                'longitude',
                NumberType::class,
                array(
                    'label' =>  'Longitude'
                )
            )
            ->add(
                'address',
                TextType::class,
                array(
                    'label' => ' ',
                    'required' => false,
                    'attr' => array(
                        'placeholder' => 'Adresse',
                    )
                )
            )
            ->add(
                'complement',
                TextType::class,
                array(
                    'label' => ' ',
                    'required'   => false,
                    'attr' => array(
                        'placeholder' => "Complément d'adresse",
                    )
                )
            )
            ->add(
                'zipcode',
                TextType::class,
                array(
                    'label' => ' ',
                    'required' => false,
                    'attr' => array(
                        'placeholder' => 'Code postal',
                    )
                )
            )
            ->add(
                'city',
                TextType::class,
                array(
                    'label' => ' ',
                    'required' => false,
                    'attr' => array(
                        'placeholder' => 'Ville',
                    )
                )
            )
            ->add(
                'region',
                TextType::class,
                array(
                    'label' => ' ',
                    'required' => false,
                    'attr' => array(
                        'placeholder' => 'Région',
                    )
                )
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Capture::class
        ));
    }
}
