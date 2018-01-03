<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Service
 *
 * @ORM\Table(name="service")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ServiceRepository")
 */
class Service
{

    /**
     * Many services have One TaxRate.
     * @ORM\ManyToOne(targetEntity="TaxRate", inversedBy="services")
     * @ORM\JoinColumn(name="taxRate_id", referencedColumnName="id")
     */
    private $taxRate;

    /**
     * Many services have One category.
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Category", inversedBy="services")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;

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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var decimal
     *
     * @ORM\Column(name="value", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $value;

    /**
     * @var string|null
     *
     * @ORM\Column(name="reference", type="string", length=255, nullable=true)
     */
    private $reference;



    /**
     * One services have Many CommandsServices.
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\CommandsServices", mappedBy="service", cascade={"persist"})
     */
    private $commandsServices;


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
     * Set name.
     *
     * @param string $name
     *
     * @return Service
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set value.
     *
     * @param decimal|null $value
     *
     * @return Service
     */
    public function setValue($value = null)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value.
     *
     * @return decimal|null
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set reference.
     *
     * @param string|null $reference
     *
     * @return Service
     */
    public function setReference($reference = null)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference.
     *
     * @return string|null
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * retourne le calcul de la tva
     * @return decimal
     * @author : Charles-emmanuel DEZANDEE <cdezandee@sigma.fr>
     */
    public function getTotalTva(){
        return $this->getValue() * $this->getTaxRate()->getValue();
    }

    /**
     * retourne le calcul du total ttc
     * @return decimal
     * @author : Charles-emmanuel DEZANDEE <cdezandee@sigma.fr>
     */
    public function getTotalTtc(){
        return $this->getValue() + $this->getTotalTva();
    }

    /**
     * Set taxRate.
     *
     * @param \AppBundle\Entity\TaxRate|null $taxRate
     *
     * @return Service
     */
    public function setTaxRate(\AppBundle\Entity\TaxRate $taxRate = null)
    {
        $this->taxRate = $taxRate;

        return $this;
    }

    /**
     * Get taxRate.
     *
     * @return \AppBundle\Entity\TaxRate|null
     */
    public function getTaxRate()
    {
        return $this->taxRate;
    }

    /**
     * Set category.
     *
     * @param \AppBundle\Entity\Category|null $category
     *
     * @return Service
     */
    public function setCategory(\AppBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category.
     *
     * @return \AppBundle\Entity\Category|null
     */
    public function getCategory()
    {
        return $this->category;
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
     * @return Service
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
}
