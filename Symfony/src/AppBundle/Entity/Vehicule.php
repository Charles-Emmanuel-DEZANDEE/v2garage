<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Vehicule
 *
 * @ORM\Table(name="vehicule")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\VehiculeRepository")
 */
class Vehicule
{

    public function __construct()
    {
        // Par défaut, le client est actif
        $this->isActive = true;
        // Par défaut, la date de l'annonce est la date d'aujourd'hui
        $this->createDate = new \Datetime();
    }
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
     * @ORM\Column(name="vin", type="string", length=255)
     */
    private $vin;

    /**
     * @var string
     *
     * @ORM\Column(name="registration", type="string", length=255)
     */
    private $registration;

    /**
     * @var int
     *
     * @ORM\Column(name="mileage", type="integer")
     */
    private $mileage;

    /**
     * @var date_immutable
     *
     * @ORM\Column(name="circulation_launch_date", type="date_immutable")
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
        $this->brand = $brand;

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
        $this->vin = $vin;

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
        $this->registration = $registration;

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
     * Set circulationLaunchDate.
     *
     * @param date_immutable $circulationLaunchDate
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
     * @return date_immutable
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
}