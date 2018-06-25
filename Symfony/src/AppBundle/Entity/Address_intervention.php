<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * address_intervention
 *
 * @ORM\Table(name="address_intervention")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\address_interventionRepository")
 */
class Address_intervention
{

    /**
     * Many addresses have One Customer.
     * @ORM\ManyToOne(targetEntity="Customer", inversedBy="addresses")
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
     * @ORM\Column(name="name", type="string", length=45)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="number", type="string", length=45, nullable=true)
     */
    private $number;


    /**
     * @var string
     *
     * @ORM\Column(name="road_1", type="string", length=255)
     */
    private $road1;

    /**
     * @var string|null
     *
     * @ORM\Column(name="road_2", type="string", length=255, nullable=true)
     */
    private $road2;

    /**
     * @var int
     *
     * @ORM\Column(name="zipcode", type="integer")
     */
    private $zipcode;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255)
     */
    private $city;

    /**
     * @var string|null
     *
     * @ORM\Column(name="region", type="string", length=255, nullable=true)
     */
    private $region;

    /**
     * @var string|null
     *
     * @ORM\Column(name="country", type="string", length=255, nullable=true)
     */
    private $country;

    /**
     * One paymentType has Many commands.
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Command", mappedBy="adressIntervention", cascade={"persist"})
     */
    private $commands;


    /**
     * Address_intervention constructor.
     * @param $customer
     */
    public function __construct(Customer $customer)
    {
        $this->setCustomer($customer);
        $this->commands = new ArrayCollection();
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
     * Set name.
     *
     * @param string $name
     *
     * @return address_intervention
     */
    public function setName($name)
    {
        $this->name = ucwords($name);

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
     * Set number.
     *
     * @param string|null $number
     *
     * @return address_intervention
     */
    public function setNumber($number = null)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number.
     *
     * @return string|null
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set socialReason.
     *
     * @param string|null $socialReason
     *
     * @return address_intervention
     */
    public function setSocialReason($socialReason = null)
    {
        $this->socialReason = $socialReason;

        return $this;
    }

    /**
     * Get socialReason.
     *
     * @return string|null
     */
    public function getSocialReason()
    {
        return $this->socialReason;
    }

    /**
     * Set road1.
     *
     * @param string $road1
     *
     * @return address_intervention
     */
    public function setRoad1($road1)
    {
        $this->road1 = $road1;

        return $this;
    }

    /**
     * Get road1.
     *
     * @return string
     */
    public function getRoad1()
    {
        return $this->road1;
    }

    /**
     * Set road2.
     *
     * @param string|null $road2
     *
     * @return address_intervention
     */
    public function setRoad2($road2 = null)
    {
        $this->road2 = $road2;

        return $this;
    }

    /**
     * Get road2.
     *
     * @return string|null
     */
    public function getRoad2()
    {
        return $this->road2;
    }

    /**
     * Set zipcode.
     *
     * @param int $zipcode
     *
     * @return address_intervention
     */
    public function setZipcode($zipcode)
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    /**
     * Get zipcode.
     *
     * @return int
     */
    public function getZipcode()
    {
        return $this->zipcode;
    }

    /**
     * Set city.
     *
     * @param string $city
     *
     * @return address_intervention
     */
    public function setCity($city)
    {
        $this->city = ucwords($city);

        return $this;
    }

    /**
     * Get city.
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set region.
     *
     * @param string|null $region
     *
     * @return address_intervention
     */
    public function setRegion($region = null)
    {
        $this->region = ucwords($region);

        return $this;
    }

    /**
     * Get region.
     *
     * @return string|null
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set country.
     *
     * @param string|null $country
     *
     * @return address_intervention
     */
    public function setCountry($country = null)
    {
        $this->country = ucwords($country);

        return $this;
    }

    /**
     * Get country.
     *
     * @return string|null
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set customer.
     *
     * @param \AppBundle\Entity\Customer|null $customer
     *
     * @return Address_intervention
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
     * Add command.
     *
     * @param \AppBundle\Entity\Command $command
     *
     * @return Address_intervention
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
}
