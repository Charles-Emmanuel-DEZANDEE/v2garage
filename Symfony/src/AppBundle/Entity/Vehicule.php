<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Vehicule
 *
 * @ORM\Table(name="vehicule")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\VehiculeRepository")
 */
class Vehicule
{

    /**
     * Many Vehicules have One Customer.
     * @ORM\ManyToOne(targetEntity="Customer", inversedBy="vehicules")
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
     * @ORM\Column(name="brand", type="string", length=255)
     */
    private $brand;

    /**
     * @var string
     *
     * @ORM\Column(name="model", type="string", length=255)
     */
    private $model;

    /**
     * @var string
     *
     * @ORM\Column(name="vin", type="string", length=255, nullable=true)
     */
    private $vin;

    /**
     * @var string
     *
     * @ORM\Column(name="registration", type="string", length=255, unique=true)
     */
    private $registration;


    /**
     * @var datetime
     *
     * @ORM\Column(name="circulation_launch_date", type="datetime", nullable=true)
     */
    private $circulationLaunchDate;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="last_control_date", type="datetime", nullable=true)
     */
    private $lastControlDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_date", type="datetime")
     */
    private $createDate;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * One vehicule has Many commands.
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Command", mappedBy="vehicule", cascade={"persist"})
     */
    private $commands;

    public function __construct(Customer $customer)
    {
        // Par défaut, le client est actif
        $this->isActive = true;
        // Par défaut, la date de l'annonce est la date d'aujourd'hui
        $this->createDate = new \Datetime();
        // un véhicule est obligatoire rattaché à un client
        $this->setCustomer($customer);
        $this->commands = new ArrayCollection();
    }
    /**
     * Add command.
     *
     * @param \AppBundle\Entity\Command $command
     *
     * @return Vehicule
     */
    public function addCommand(\AppBundle\Entity\Command $command)
    {
        $this->commands[] = $command;

        return $this;
    }

    /**
     * Remove command.
     *
     * @param \AppBundle\Entity\Command $command
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeCommand(\AppBundle\Entity\Command $command)
    {
        return $this->commands->removeElement($command);
    }

    /**
     * Get commands.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCommands()
    {
        return $this->commands;
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
     * Set brand.
     *
     * @param string $brand
     *
     * @return Vehicule
     */
    public function setBrand($brand)
    {
        $this->brand = ucwords($brand);

        return $this;
    }

    /**
     * Get brand.
     *
     * @return string
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * Set model.
     *
     * @param string $model
     *
     * @return Vehicule
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Get model.
     *
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set vin.
     *
     * @param string $vin
     *
     * @return Vehicule
     */
    public function setVin($vin)
    {
        $this->vin = strtoupper($vin);

        return $this;
    }

    /**
     * Get vin.
     *
     * @return string
     */
    public function getVin()
    {
        return $this->vin;
    }

    /**
     * Set registration.
     *
     * @param string $registration
     *
     * @return Vehicule
     */
    public function setRegistration($registration)
    {
        $this->registration = strtoupper($registration);

        return $this;
    }

    /**
     * Get registration.
     *
     * @return string
     */
    public function getRegistration()
    {
        return $this->registration;
    }

    /**
     * Set circulationLaunchDate.
     *
     * @param datetime $circulationLaunchDate
     *
     * @return Vehicule
     */
    public function setCirculationLaunchDate($circulationLaunchDate)
    {
        $this->circulationLaunchDate = $circulationLaunchDate;

        return $this;
    }

    /**
     * Get circulationLaunchDate.
     *
     * @return datetime
     */
    public function getCirculationLaunchDate()
    {
        return $this->circulationLaunchDate;
    }

    /**
     * Set lastControlDate.
     *
     * @param \DateTime|null $lastControlDate
     *
     * @return Vehicule
     */
    public function setLastControlDate($lastControlDate = null)
    {
        $this->lastControlDate = $lastControlDate;

        return $this;
    }

    /**
     * Get lastControlDate.
     *
     * @return \DateTime|null
     */
    public function getLastControlDate()
    {
        return $this->lastControlDate;
    }

    /**
     * Set createDate.
     *
     * @param \DateTime $createDate
     *
     * @return Vehicule
     */
    public function setCreateDate($createDate)
    {
        $this->createDate = $createDate;

        return $this;
    }

    /**
     * Get createDate.
     *
     * @return \DateTime
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }

    /**
     * Set isActive.
     *
     * @param bool $isActive
     *
     * @return Vehicule
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive.
     *
     * @return bool
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set customer.
     *
     * @param \AppBundle\Entity\Customer|null $customer
     *
     * @return Vehicule
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
}
