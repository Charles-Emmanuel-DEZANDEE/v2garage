<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Command
 *
 * @ORM\Table(name="command")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CommandRepository")
 */
class Command
{


    /**
     * Many commands have One Customer.
     * @ORM\ManyToOne(targetEntity="Customer", inversedBy="commands")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     */
    private $customer;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="ref", type="string", length=50, unique=true)
     */
    private $ref;

    /**
     * @var string|null
     *
     * @ORM\Column(name="bill_ref", type="string", length=50, nullable=true, unique=true)
     */
    private $billRef;

    /**
     * @var decimal
     *
     * @ORM\Column(name="total_ht", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $totalHt;

    /**
     * @var decimal|null
     *
     * @ORM\Column(name="total_tva", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $totalTva;

    /**
     * @var decimal|null
     *
     * @ORM\Column(name="total_ttc", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $totalTtc;

    /**
     * @var decimal|null
     *
     * @ORM\Column(name="total_discount", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $totalDiscount;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_create", type="datetime")
     */
    private $dateCreate;

    /**
     * @var bool
     *
     * @ORM\Column(name="commande_validate", type="boolean")
     */
    private $commandeValidate;

    /**
     * @var datetime|null
     *
     * @ORM\Column(name="date_last_update", type="datetime", nullable=true)
     */
    private $dateLastUpdate;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_bill", type="datetime", nullable=true)
     */
    private $dateBill;

    /**
     * one command have Many CommandsServices.
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\CommandsServices", mappedBy="command", cascade={"persist"})
          */
    private $commandsServices;


    /**
     * @param $value
     * @return decimal
     * @author : Charles-emmanuel DEZANDEE <cdezandee@sigma.fr>
     */
    private function getround($value){
        return round($value,2);
    }

    /**
     * fait les totaux ht, ttc et remise
     *
     * @author : Charles-emmanuel DEZANDEE <cdezandee@sigma.fr>
     */
    public function setTotalAll(){
        $totalTtc =0;
        $totalTva =0;
        $totalDiscount =0;
        $listlignes = $this->getCommandsServices();
        foreach ($listlignes as $ligne){
            $totalTva += $ligne->getTotalTva();
            $totalTtc += $ligne->getTotalTtc();
            $totalDiscount += $ligne->getTotalDiscount();
        }
        //on set les totaux
        $this->setTotalTva($totalTva);
        $this->setTotalTtc($totalTtc);
        $this->setTotalDiscount($totalDiscount);
    }


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set ref.
     *
     * @param string $ref
     *
     * @return Command
     */
    public function setRef($ref)
    {
        $this->ref = $ref;

        return $this;
    }

    /**
     * Get ref.
     *
     * @return string
     */
    public function getRef()
    {
        return $this->ref;
    }

    /**
     * Set billRef.
     *
     * @param string|null $billRef
     *
     * @return Command
     */
    public function setBillRef($billRef = null)
    {
        $this->billRef = $billRef;

        return $this;
    }

    /**
     * Get billRef.
     *
     * @return string|null
     */
    public function getBillRef()
    {
        return $this->billRef;
    }

    /**
     * Set totalHt.
     *
     * @param string $totalHt
     *
     * @return Command
     */
    public function setTotalHt($totalHt)
    {
        $this->totalHt = $totalHt;

        return $this;
    }

    /**
     * Get totalHt.
     *
     * @return string
     */
    public function getTotalHt()
    {
        return $this->totalHt;
    }

    /**
     * Set totalTva.
     *
     * @param string|null $totalTva
     *
     * @return Command
     */
    public function setTotalTva($totalTva = null)
    {
        $this->totalTva = $totalTva;

        return $this;
    }

    /**
     * Get totalTva.
     *
     * @return string|null
     */
    public function getTotalTva()
    {
        return $this->totalTva;
    }

    /**
     * Set totalTtc.
     *
     * @param string|null $totalTtc
     *
     * @return Command
     */
    public function setTotalTtc($totalTtc = null)
    {
        $this->totalTtc = $totalTtc;

        return $this;
    }

    /**
     * Get totalTtc.
     *
     * @return string|null
     */
    public function getTotalTtc()
    {
        return $this->totalTtc;
    }

    /**
     * Set dateCreate.
     *
     * @param \DateTime $dateCreate
     *
     * @return Command
     */
    public function setDateCreate($dateCreate)
    {
        $this->dateCreate = $dateCreate;

        return $this;
    }

    /**
     * Get dateCreate.
     *
     * @return \DateTime
     */
    public function getDateCreate()
    {
        return $this->dateCreate;
    }

    /**
     * Set commandeValidate.
     *
     * @param bool $commandeValidate
     *
     * @return Command
     */
    public function setCommandeValidate($commandeValidate)
    {
        $this->commandeValidate = $commandeValidate;

        return $this;
    }

    /**
     * Get commandeValidate.
     *
     * @return bool
     */
    public function getCommandeValidate()
    {
        return $this->commandeValidate;
    }

    /**
     * Set dateLastUpdate.
     *
     * @param datetime|null $dateLastUpdate
     *
     * @return Command
     */
    public function setDateLastUpdate($dateLastUpdate = null)
    {
        $this->dateLastUpdate = $dateLastUpdate;

        return $this;
    }

    /**
     * Get dateLastUpdate.
     *
     * @return datetime|null
     */
    public function getDateLastUpdate()
    {
        return $this->dateLastUpdate;
    }

    /**
     * Set dateBill.
     *
     * @param \DateTime|null $dateBill
     *
     * @return Command
     */
    public function setDateBill($dateBill = null)
    {
        $this->dateBill = $dateBill;

        return $this;
    }

    /**
     * Get dateBill.
     *
     * @return \DateTime|null
     */
    public function getDateBill()
    {
        return $this->dateBill;
    }

    /**
     * Set customer.
     *
     * @param \AppBundle\Entity\Customer|null $customer
     *
     * @return Command
     */
    public function setCustomer(\AppBundle\Entity\Customer $customer = null)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer.
     *
     * @return \AppBundle\Entity\Customer|null
     */
    public function getCustomer()
    {
        return $this->customer;
    }


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->commandsServices = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add commandsService.
     *
     * @param \AppBundle\Entity\CommandsServices $commandsService
     *
     * @return Command
     */
    public function addCommandsService(\AppBundle\Entity\CommandsServices $commandsService)
    {
        $this->commandsServices[] = $commandsService;

        return $this;
    }

    /**
     * Remove commandsService.
     *
     * @param \AppBundle\Entity\CommandsServices $commandsService
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeCommandsService(\AppBundle\Entity\CommandsServices $commandsService)
    {
        return $this->commandsServices->removeElement($commandsService);
    }

    /**
     * Get commandsServices.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCommandsServices()
    {
        return $this->commandsServices;
    }

    /**
     * Set totalDiscount.
     *
     * @param string|null $totalDiscount
     *
     * @return Command
     */
    public function setTotalDiscount($totalDiscount = null)
    {
        $this->totalDiscount = $totalDiscount;

        return $this;
    }

    /**
     * Get totalDiscount.
     *
     * @return string|null
     */
    public function getTotalDiscount()
    {
        return $this->totalDiscount;
    }
}
