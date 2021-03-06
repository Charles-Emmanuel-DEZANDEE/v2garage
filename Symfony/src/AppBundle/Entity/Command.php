<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Command
 *
 * @ORM\Table(name="command")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CommandRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Command
{


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
     * @var \DateTime|null
     *
     * @ORM\Column(name="commande_validate", type="datetime", nullable=true)
     */
    private $commandeValidate;

    /**
     * @var \DateTime|null
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
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_bill_acquited", type="datetime", nullable=true)
     */
    private $dateBillAcquited;

    /** champ observation
     * @var string|null
     *
     * @ORM\Column(name="note", type="text", nullable=true)
     */
    private $note;

    /**
     * @var int
     *
     * @ORM\Column(name="mileage", type="integer", nullable=true)
     */
    private $mileage;

    /**
     * Many commands have One Vehicule.
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Vehicule", inversedBy="commands")
     * @ORM\JoinColumn(name="vehicule_id", referencedColumnName="id")
     */
    private $vehicule;

    /**
     * one command have Many CommandsServices.
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\CommandsServices", mappedBy="command", cascade={"persist"}, cascade={"remove"})
     */
    private $commandsServices;

    /**
     * Many commands have One paymentType.
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\PaymentType", inversedBy="commands")
     * @ORM\JoinColumn(name="paymentType_id", referencedColumnName="id")
     */
    private $paymentType;

    /**
     * Many commands have One adress intervention.
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Address_intervention", inversedBy="commands")
     * @ORM\JoinColumn(name="adressintervention_id", referencedColumnName="id")
     */
    private $adressIntervention;





    /**
     * @param $value
     * @return decimal
     * @author : Charles-emmanuel DEZANDEE <cdezandee@gmail.com>
     */
    private function getround($value)
    {
        return round($value, 2);
    }


    /**
     * @ORM\PreUpdate
     */
    public function updateDate()
    {
        $this->setDateLastUpdate(new \Datetime());

    }

    /**
     * initialise l'id en vue d'une duplication
     * @author : Charles-emmanuel DEZANDEE <cdezandee@gmail.com>
     */
    public function nullId()
    {
        $this->id = null;
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
     * @return \DateTime|null
     */
    public function getDateBillAcquited()
    {
        return $this->dateBillAcquited;
    }

    /**
     * @param \DateTime|null $dateBillAcquited
     */
    public function setDateBillAcquited($dateBillAcquited)
    {
        $this->dateBillAcquited = $dateBillAcquited;
    }

    /**
     * Set mileage.
     *
     * @param int $mileage
     *
     * @return Vehicule
     */
    public function setMileage($mileage)
    {
        $this->mileage = $mileage;

        return $this;
    }

    /**
     * Get mileage.
     *
     * @return int
     */
    public function getMileage()
    {
        return $this->mileage;
    }

    /**
     * Set vehicule.
     *
     * @param \AppBundle\Entity\Vehicule|null $vehicule
     *
     * @return Command
     */
    public function setVehicule(\AppBundle\Entity\Vehicule $vehicule = null)
    {
        $this->vehicule = $vehicule;

        return $this;
    }

    /**
     * Get vehicule.
     *
     * @return \AppBundle\Entity\Vehicule|null
     */
    public function getVehicule()
    {
        return $this->vehicule;
    }


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->commandsServices = new \Doctrine\Common\Collections\ArrayCollection();
        $this->dateCreate = new \DateTime();
        $this->dateLastUpdate = new \DateTime();
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

    /**
     * Set paymentType.
     *
     * @param \AppBundle\Entity\PaymentType|null $paymentType
     *
     * @return Command
     */
    public function setPaymentType(\AppBundle\Entity\PaymentType $paymentType = null)
    {
        $this->paymentType = $paymentType;

        return $this;
    }

    /**
     * Get paymentType.
     *
     * @return \AppBundle\Entity\PaymentType|null
     */
    public function getPaymentType()
    {
        return $this->paymentType;
    }

    /**
     * Set commandeValidate.
     *
     * @param \DateTime|null $commandeValidate
     *
     * @return Command
     */
    public function setCommandeValidate($commandeValidate = null)
    {
        $this->commandeValidate = $commandeValidate;

        return $this;
    }

    /**
     * Get commandeValidate.
     *
     * @return \DateTime|null
     */
    public function getCommandeValidate()
    {
        return $this->commandeValidate;
    }

    /**
     * Set note.
     *
     * @param string|null $note
     *
     * @return Command
     */
    public function setNote($note = null)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note.
     *
     * @return string|null
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set adressIntervention.
     *
     * @param \AppBundle\Entity\Address_intervention|null $adressIntervention
     *
     * @return Command
     */
    public function setAdressIntervention(\AppBundle\Entity\Address_intervention $adressIntervention = null)
    {
        $this->adressIntervention = $adressIntervention;

        return $this;
    }

    /**
     * Get adressIntervention.
     *
     * @return \AppBundle\Entity\Address_intervention|null
     */
    public function getAdressIntervention()
    {
        return $this->adressIntervention;
    }
}
