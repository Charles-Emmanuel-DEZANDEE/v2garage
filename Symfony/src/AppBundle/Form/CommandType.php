<?php

namespace AppBundle\Form;

use AppBundle\Subscriber\CommandFormSubscriber;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            /*->add('ref')
            ->add('billRef')
            ->add('totalHt')
            ->add('totalTva')
            ->add('totalTtc')
            ->add('totalDiscount')
            ->add('dateCreate')*/
            ->add('commandeValidate', DateType::class)
            /*->add('dateLastUpdate')*/
            ->add('dateBill', DateType::class)
            ->add('paymentType', EntityType::class, array(
                // looks for choices from this entity
                'class' => 'AppBundle:PaymentType',

                // uses the User.username property as the visible option string
                'choice_label' => 'name',

                // used to render a select box, check boxes or radios
                // 'multiple' => true,
                 'expanded' => true,
            ))
            ->add('adressIntervention', EntityType::class, array(
                // looks for choices from this entity
                'class' => 'AppBundle:Address_intervention',

                // uses the User.username property as the visible option string
                'choice_label' => 'name',

                // used to render a select box, check boxes or radios
                // 'multiple' => true,
                'expanded' => true,
            ))
            /*->add('customer')*/
            ->add('note',TextType::class)

        ;

        // souscripteur
        //$builder->addEventSubscriber(new CommandFormSubscriber());
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Command'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_command';
    }


}
