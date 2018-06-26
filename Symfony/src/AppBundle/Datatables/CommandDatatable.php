<?php

namespace AppBundle\Datatables;

use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Style;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\BooleanColumn;
use Sg\DatatablesBundle\Datatable\Column\ActionColumn;
use Sg\DatatablesBundle\Datatable\Column\MultiselectColumn;
use Sg\DatatablesBundle\Datatable\Column\VirtualColumn;
use Sg\DatatablesBundle\Datatable\Column\DateTimeColumn;
use Sg\DatatablesBundle\Datatable\Column\ImageColumn;
use Sg\DatatablesBundle\Datatable\Filter\TextFilter;
use Sg\DatatablesBundle\Datatable\Filter\NumberFilter;
use Sg\DatatablesBundle\Datatable\Filter\SelectFilter;
use Sg\DatatablesBundle\Datatable\Filter\DateRangeFilter;
use Sg\DatatablesBundle\Datatable\Editable\CombodateEditable;
use Sg\DatatablesBundle\Datatable\Editable\SelectEditable;
use Sg\DatatablesBundle\Datatable\Editable\TextareaEditable;
use Sg\DatatablesBundle\Datatable\Editable\TextEditable;

/**
 * Class CommandDatatable
 *
 * @package AppBundle\Datatables
 */
class CommandDatatable extends AbstractDatatable
{
    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function buildDatatable(array $options = array())
    {
        try {
            $this->language->set(array(
                'cdn_language_by_locale' => true
                //'language' => 'de'
            ));
        } catch (\Exception $e) {
        }

        /*        $this->extensions->set(array(
                    'responsive' => true,
                ));*/

        $this->ajax->set(array());

        $this->options->set(array(
            /*            'classes' => Style::BOOTSTRAP_3_STYLE,*/
            /*            'stripe_classes' => [ 'strip1', 'strip2', 'strip3' ],*/
            'individual_filtering' => false,
            'individual_filtering_position' => 'head',
            'order' => array(array(0, 'asc')),
            'order_cells_top' => true,
            //'global_search_type' => 'gt',
            'search_in_non_visible_columns' => true,
        ));

        $this->features->set(array());

        $this->columnBuilder
            /*            ->add('id', Column::class, array(
                            'title' => 'Id',
                            ))*/
            ->add('ref', Column::class, array(
                'title' => 'Référence Devis',
                'width' => '8%',

                'responsive_priority' => 1,
            ))
            ->add('billRef', Column::class, array(
                'title' => 'Référence Facture',
                'width' => '8%',
            ))
            /*            ->add('totalHt', Column::class, array(
                            'title' => 'Total Ht',
                            'width' => '25px',
                        ))
                        ->add('totalTva', Column::class, array(
                            'title' => 'Total Tva',
                            'width' => '25px',

                        ))
                        ->add('totalTtc', Column::class, array(
                            'title' => 'Total Ttc',
                            'width' => '25px',

                        ))
                        ->add('totalDiscount', Column::class, array(
                            'title' => 'Total Remise',
                            'width' => '25px',

                        ))*/
            ->add('dateCreate', DateTimeColumn::class, array(
                'title' => 'Devis créé le',
                'width' => '12%',
                'date_format' => 'D MMMM YYYY',

            ))
            ->add('dateBill', DateTimeColumn::class, array(
                'title' => 'Facture créé le',
                'width' => '12%',
                'date_format' => 'D MMMM YYYY',

            ))
            ->add('commandeValidate', DateTimeColumn::class, array(
                'title' => 'Devis accepté',
                'width' => '12%',
                'date_format' => 'D MMMM YYYY',

            ))
            /* ->add('dateLastUpdate', DateTimeColumn::class, array(
                 'title' => 'DateLastUpdate',
                 ))*/
            ->add('dateBillAcquited', DateTimeColumn::class, array(
                'title' => 'Facture payée le',
                'width' => '12%',
                'default_content' => 'Non réglée',
                'date_format' => 'D MMMM YYYY',

            ))
            /*            ->add('note', Column::class, array(
                            'title' => 'Note',
                        ))*/
            /*          ->add('vehicule.id', Column::class, array(
                          'title' => 'Vehicule Id',
                          ))
                      ->add('vehicule.brand', Column::class, array(
                          'title' => 'Vehicule Brand',
                          ))
                      ->add('vehicule.model', Column::class, array(
                          'title' => 'Vehicule Model',
                          ))
                      ->add('vehicule.vin', Column::class, array(
                          'title' => 'Vehicule Vin',
                          ))
                      ->add('vehicule.registration', Column::class, array(
                          'title' => 'Vehicule Registration',
                          ))
                      ->add('vehicule.mileage', Column::class, array(
                          'title' => 'Vehicule Mileage',
                          ))
                      ->add('vehicule.circulationLaunchDate', Column::class, array(
                          'title' => 'Vehicule CirculationLaunchDate',
                          ))
                      ->add('vehicule.lastControlDate', Column::class, array(
                          'title' => 'Vehicule LastControlDate',
                          ))
                      ->add('vehicule.createDate', Column::class, array(
                          'title' => 'Vehicule CreateDate',
                          ))
                      ->add('vehicule.isActive', Column::class, array(
                          'title' => 'Vehicule IsActive',
                          ))
                      ->add('commandsServices.id', Column::class, array(
                          'title' => 'CommandsServices Id',
                          'data' => 'commandsServices[, ].id'
                          ))
                      ->add('commandsServices.quantity', Column::class, array(
                          'title' => 'CommandsServices Quantity',
                          'data' => 'commandsServices[, ].quantity'
                          ))
                      ->add('commandsServices.value', Column::class, array(
                          'title' => 'CommandsServices Value',
                          'data' => 'commandsServices[, ].value'
                          ))
                      ->add('commandsServices.taxRate', Column::class, array(
                          'title' => 'CommandsServices TaxRate',
                          'data' => 'commandsServices[, ].taxRate'
                          ))
                      ->add('commandsServices.discountRate', Column::class, array(
                          'title' => 'CommandsServices DiscountRate',
                          'data' => 'commandsServices[, ].discountRate'
                          ))
                      ->add('commandsServices.reference', Column::class, array(
                          'title' => 'CommandsServices Reference',
                          'data' => 'commandsServices[, ].reference'
                          ))
                      ->add('paymentType.id', Column::class, array(
                          'title' => 'PaymentType Id',
                          ))*/
            /*            ->add('paymentType.name', Column::class, array(
                            'title' => 'Payé avec',
                            ))*/

            ->add(null, ActionColumn::class, array(
                'title' => $this->translator->trans('sg.datatables.actions.title'),
                'width' => '20%',

                'actions' => array(
                    array(
                        'route' => 'app_admin_command_view',
                        'route_parameters' => array(
                            'id' => 'id'
                        ),
                        'label' => $this->translator->trans('sg.datatables.actions.show'),
                        'icon' => 'glyphicon glyphicon-eye-open',
                        'attributes' => array(
                            'rel' => 'tooltip',
                            'title' => $this->translator->trans('sg.datatables.actions.show'),
                            'class' => 'btn btn-primary btn-xs',
                            'role' => 'button'
                        ),
                    ),
                    array(
                        'route' => 'command_edit',
                        'route_parameters' => array(
                            'id' => 'id'
                        ),
                        'label' => $this->translator->trans('sg.datatables.actions.edit'),
                        'icon' => 'glyphicon glyphicon-edit',
                        'attributes' => array(
                            'rel' => 'tooltip',
                            'title' => $this->translator->trans('sg.datatables.actions.edit'),
                            'class' => 'btn btn-primary btn-xs',
                            'role' => 'button'
                        ),
                    )
                )
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'AppBundle\Entity\Command';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'command_datatable';
    }
}
