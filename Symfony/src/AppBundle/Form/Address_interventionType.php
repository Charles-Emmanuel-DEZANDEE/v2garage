<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class Address_interventionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', ChoiceType::class, array(
                'choices' => [
                    'Domicile' => 'Domicile',
                    'Travail' => 'Travail',
                    'Lieu de vacances' => 'Lieu de vacances',
                    'Autre' => 'Autre'
                ])
            )
            ->add('number', TextType::class)
            ->add('road1', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Adresse requise'
                    ])
                ]
            ])
            ->add('road2', TextType::class)
            ->add('zipcode', IntegerType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Code postal requis'
                    ])
                ]
            ])
            ->add('city', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Numéro d\'immatriculation requis'
                    ])
                ]
            ])
            ->add('region', TextType::class)
            ->add('country', TextType::class)
        ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Address_intervention'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_address_intervention';
    }


}
