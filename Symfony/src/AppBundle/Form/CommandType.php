<?php

namespace AppBundle\Form;

use AppBundle\Repository\address_interventionRepository;
use AppBundle\Subscriber\CommandFormSubscriber;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
            ->add('commandeValidate', DateType::class,[
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
            ])
            /*->add('dateLastUpdate')*/
            ->add('dateBillAcquited', DateType::class,
                [
                    'widget' =>  'single_text',
                    'format' => 'dd-MM-yyyy',
                ])
            ->add('paymentType', EntityType::class, array(
                // looks for choices from this entity
                'class' => 'AppBundle:PaymentType',
                'choice_label' => 'name',
                'placeholder' => 'Choisir un paiement',

                /*'expanded' => true,*/
            ))

            ->add('adressIntervention', EntityType::class, array(
                // looks for choices from this entity
                'class' => 'AppBundle:Address_intervention',

                'choice_label' => 'name',

                /*'expanded' => true,*/

                                'query_builder' => function (address_interventionRepository $repo) use ($options) {
                                    return $repo->findByCustomer($options['attr']['idCustomer']);
                                }
            ))
            /*->add('customer')*/
            ->add('note', TextareaType::class);

        // souscripteur
        //$builder->addEventSubscriber(new CommandFormSubscriber());
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
