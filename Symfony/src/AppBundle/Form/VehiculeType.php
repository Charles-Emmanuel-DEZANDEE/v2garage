<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class VehiculeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('brand', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Marque requise'
                    ])
                ]
            ])
            ->add('model', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Modéle requis'
                    ])
                ]
            ])
            ->add('vin', TextType::class)
            ->add('registration', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Numéro d\'immatriculation requis'
                    ])
                ]
            ])
            ->add('mileage', NumberType::class)
            ->add('circulationLaunchDate', DateTimeType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Date de mise en circulation requise'
                    ])
                ],
                'widget' => 'single_text',

                // do not render as type="date", to avoid HTML5 date pickers
                'html5' => false,

                // add a class that can be selected in JavaScript
                'attr' => ['class' => 'js-datepicker'],
            ])
            ->add('lastControlDate',DateTimeType::class, [
                'widget' => 'single_text',

                // do not render as type="date", to avoid HTML5 date pickers
                'html5' => false,

                // add a class that can be selected in JavaScript
                'attr' => ['class' => 'js-datepicker'],
            ])
            ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Vehicule'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_vehicule';
    }


}
