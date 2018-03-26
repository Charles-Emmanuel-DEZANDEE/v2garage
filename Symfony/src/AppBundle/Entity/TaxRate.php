<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * TaxRate
 *
 * @ORM\Table(name="tax_rate")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TaxRateRepository")
 */
class TaxRate
{

    /**
     * One taxRate has Many services.
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Service", mappedBy="taxRate", cascade={"persist"})
     */
    private $services;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @var decimal
     *
     * @ORM\Column(name="value", type="decimal", precision=10, scale=2)
     */
    private $value;

    public function __toString() : string
    {
        return $this->getValue();
    }

    public function __construct()
    {
        $this->services = new ArrayCollection();
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
     * Set value.
     *
     * @param decimal $value
     *
     * @return TaxRate
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value.
     *
     * @return decimal
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Add service.
     *
     * @param \AppBundle\Entity\Service $service
     *
     * @return TaxRate
     */
    public function addService(\AppBundle\Entity\Service $service)
    {
        $this->services[] = $service;

        return $this;
    }

    /**
     * Remove service.
     *
     * @param \AppBundle\Entity\Service $service
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeService(\AppBundle\Entity\Service $service)
    {
        return $this->services->removeElement($service);
    }

    /**
     * Get services.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServices()
    {
        return $this->services;
    }
}
