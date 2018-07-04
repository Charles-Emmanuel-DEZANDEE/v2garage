<?php

namespace AppBundle\Form;

use AppBundle\Repository\address_interventionRepository;
use AppBundle\Subscriber\CommandFormSubscriber;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NouvelleInterventionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mileage', NumberType::class)
            ->add('adressIntervention', EntityType::class, array(
                // looks for choices from this entity
                'class' => 'AppBundle:Address_intervention',

                'choice_label' => 'name',

                /*'expanded' => true,*/

                                'query_builder' => function (address_interventionRepository $repo) use ($options) {
                                    return $repo->findByCustomer($options['attr']['idCustomer']);
                                }
            ));


    }

    /**
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
