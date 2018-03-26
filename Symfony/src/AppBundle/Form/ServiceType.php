<?php

namespace AppBundle\Form;

use AppBundle\Entity\TaxRate;
use AppBundle\Entity\Unite;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ServiceType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'nom requis'
                    ])
                ]
            ])
            ->add('value', NumberType::class)
            ->add('unite', EntityType::class, [
                'class' => Unite::class,
                'constraints' => [
                    new NotBlank([
                        'message' => 'tva requise'
                    ])
                ]
            ])
            ->add('taxRate', EntityType::class, [
                'class' => TaxRate::class,
                'constraints' => [
                    new NotBlank([
                        'message' => 'tva requise'
                    ])
                ]
            ])
//            ->add('category')
        ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Service'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_service';
    }


}
