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

        $this->ajax->set(array(
        ));

        $this->options->set(array(
            'individual_filtering' => true,
            'individual_filtering_position' => 'head',
            'order_cells_top' => true,
        ));

        $this->features->set(array(
        ));

        $this->columnBuilder
            ->add('id', Column::class, array(
                'title' => 'Id',
                ))
            ->add('ref', Column::class, array(
                'title' => 'Ref',
                ))
            ->add('billRef', Column::class, array(
                'title' => 'BillRef',
                ))
            ->add('totalHt', Column::class, array(
                'title' => 'TotalHt',
                ))
            ->add('totalTva', Column::class, array(
                'title' => 'TotalTva',
                ))
            ->add('totalTtc', Column::class, array(
                'title' => 'TotalTtc',
                ))
            ->add('totalDiscount', Column::class, array(
                'title' => 'TotalDiscount',
                ))
            ->add('dateCreate', DateTimeColumn::class, array(
                'title' => 'DateCreate',
                ))
            ->add('commandeValidate', DateTimeColumn::class, array(
                'title' => 'CommandeValidate',
                ))
            ->add('dateLastUpdate', DateTimeColumn::class, array(
                'title' => 'DateLastUpdate',
                ))
            ->add('dateBill', DateTimeColumn::class, array(
                'title' => 'DateBill',
                ))
            ->add('dateBillAcquited', DateTimeColumn::class, array(
                'title' => 'DateBillAcquited',
                ))
            ->add('note', Column::class, array(
                'title' => 'Note',
                ))
            ->add('vehicule.id', Column::class, array(
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
/*            ->add('paymentType.id', Column::class, array(
                'title' => 'PaymentType Id',
                ))*/
            ->add('paymentType.name', Column::class, array(
                'title' => 'PaymentType Name',
                ))
            ->add(null, ActionColumn::class, array(
                'title' => $this->translator->trans('sg.datatables.actions.title'),
                'actions' => array(
                    array(
                        'route' => 'command_show',
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
            ))
        ;
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
