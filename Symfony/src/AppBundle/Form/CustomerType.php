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
            ->add('firstname', TextType::class)
            ->add('lastName', TextType::class)

            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'email requis'
                    ]),
                    new Email([
                        'message' => 'L\'email est incorrect',
                        'checkHost' => true,
                        'checkMX' => true,
                    ])
                ]
            ])
            ->add('socialReason', TextType::class)
            ->add('addressNumber', TextType::class)
            ->add('addressRoad1', TextType::class)
            ->add('addressRoad2', TextType::class)
            ->add('addressZipcode', IntegerType::class)
            ->add('addressCity', TextType::class)
            ->add('addressRegion', TextType::class)
            ->add('addressCountry', TextType::class)
            ->add('telephonePrimary', TextType::class)
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
