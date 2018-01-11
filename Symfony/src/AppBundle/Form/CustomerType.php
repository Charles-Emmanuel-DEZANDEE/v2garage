<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class CustomerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('civility', ChoiceType::class, array(
                'choices' => array(
                    'M' => 'M',
                    'Mme' => 'Mme',
                    'Mlle' => 'Mlle',
                )
            ))
            ->add('firstName', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Prénom requis'
                    ])
                ]
            ])
            ->add('lastName', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Nom requis'
                    ])
                ]
            ])

            ->add('email', EmailType::class, [
                'constraints' => [
                    new Email([
                        'message' => 'L\'email est incorrect',
                        'checkHost' => false,
                        'checkMX' => false,
                    ])
                ]
            ])
            ->add('socialReason', TextType::class)
            ->add('addressNumber', TextType::class)
            ->add('addressRoad1', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Adresse requise'
                    ])
                ]
            ])
            ->add('addressRoad2', TextType::class)
            ->add('addressZipcode', IntegerType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Code postal requis'
                    ])
                ]
            ])
            ->add('addressCity', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ville requis'
                    ])
                ]
            ])
            ->add('addressRegion', TextType::class)
            ->add('addressCountry', TextType::class)
            ->add('telephonePrimary', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Téléphone requis'
                    ])
                ]
            ])
            ->add('telephoneSecondary', TextType::class)
                    ;

    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Customer'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_user';
    }


}
