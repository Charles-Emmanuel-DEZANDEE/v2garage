<?php

namespace AppBundle\Form;

use AppBundle\Subscriber\CommandFormSubscriber;
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
            ->add('ref')
            ->add('billRef')
            ->add('totalHt')
            ->add('totalTva')
            ->add('totalTtc')
            ->add('totalDiscount')
            ->add('dateCreate')
            ->add('commandeValidate')
            ->add('dateLastUpdate')
            ->add('dateBill')
            ->add('customer');

        // souscripteur
        $builder->addEventSubscriber(new CommandFormSubscriber());
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
