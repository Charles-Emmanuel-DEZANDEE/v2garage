<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Customer
 *
 * @ORM\Table(name="customer")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CustomerRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Customer
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
     * @var string|null
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;


    /**
     * @var string|null
     *
     * @ORM\Column(name="social_reason", type="string", length=255, nullable=true)
     */
    private $socialReason;

    /**
     * @var string
     *
     * @ORM\Column(name="civility", type="string", length=6)
     */
    private $civility;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=45)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=45)
     */
    private $lastName;

    /**
     * @var datetime
     *
     * @ORM\Column(name="createDate", type="datetime")
     */
    private $createDate;

    /**
     * @var datetime
     *
     * @ORM\Column(name="lastActionDate", type="datetime")
     */
    private $lastActionDate;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @var string|null
     *
     * @ORM\Column(name="address_number", type="string", length=5, nullable=true)
     */
    private $addressNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="address_road_1", type="string", length=255)
     */
    private $addressRoad1;

    /**
     * @var string|null
     *
     * @ORM\Column(name="address_road_2", type="string", length=255, nullable=true)
     */
    private $addressRoad2;

    /**
     * @var int
     *
     * @ORM\Column(name="address_zipcode", type="integer")
     */
    private $addressZipcode;

    /**
     * @var string|null
     *
     * @ORM\Column(name="address_region", type="string", length=255, nullable=true)
     */
    private $addressRegion;

    /**
     * @var string
     *
     * @ORM\Column(name="address_city", type="string", length=255)
     */
    private $addressCity;

    /**
     * @var string|null
     *
     * @ORM\Column(name="address_country", type="string", length=255, nullable=true)
     */
    private $addressCountry;

    /**
     * @var string
     *
     * @ORM\Column(name="telephone_primary", type="string", length=45)
     */
    private $telephonePrimary;

    /**
     * @var string
     *
     * @ORM\Column(name="telephone_secondary", type="string", length=45, nullable=true)
     */
    private $telephoneSecondary;

    /**
     * One customer has Many Vehicules.
     * @ORM\OneToMany(targetEntity="Vehicule", mappedBy="customer", cascade={"persist"})
     */
    private $vehicules;

    /**
     * One customer has Many addresses.
     * @ORM\OneToMany(targetEntity="Address_intervention", mappedBy="customer", cascade={"persist"})
     */
    private $addresses;

    /**
     * One customer has Many commands.
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Command", mappedBy="customer", cascade={"persist"})
     */
    private $commands;

    public function __construct()
    {
        // Par défaut, le client est actif
        $this->isActive = true;
        // Par défaut, la date de l'annonce est la date d'aujourd'hui
        $this->createDate = new \DateTime();
        $this->lastActionDate = new \DateTime();
        $this->vehicules = new ArrayCollection();
        $this->addresses = new ArrayCollection();
        $this->commands = new ArrayCollection();
    }

    /**
     * @ORM\PreUpdate
     */
    public function updateDate()
    {
        $this->setLastActionDate(new \Datetime());
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
     * Set email.
     *
     * @param string|null $email
     *
     * @return Customer
     */
    public function setEmail($email = null)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string|null
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set firstname.
     *
     * @param string $firstname
     *
     * @return Customer
     */
    public function setFirstname($firstname)
    {
        $this->firstName = $firstname;

        return $this;
    }

    /**
     * Get firstname.
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstName;
    }

    /**
     * Set socialReason.
     *
     * @param string $socialReason
     *
     * @return Customer
     */
    public function setSocialReason($socialReason)
    {
        $this->socialReason = $socialReason;

        return $this;
    }

    /**
     * Get socialReason.
     *
     * @return string
     */
    public function getSocialReason()
    {
        return $this->socialReason;
    }

    /**
     * Set lastname.
     *
     * @param string $lastname
     *
     * @return Customer
     */
    public function setLastName($lastname)
    {
        $this->lastName = $lastname;

        return $this;
    }

    /**
     * Get lastname.
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set createDate.
     *
     * @param datetime $createDate
     *
     * @return Customer
     */
    public function setCreateDate($createDate)
    {
        $this->createDate = $createDate;

        return $this;
    }

    /**
     * Get createDate.
     *
     * @return datetime
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
     * @return Customer
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
     * Set addressNumber.
     *
     * @param string|null $addressNumber
     *
     * @return Customer
     */
    public function setAddressNumber($addressNumber = null)
    {
        $this->addressNumber = $addressNumber;

        return $this;
    }

    /**
     * Get addressNumber.
     *
     * @return string|null
     */
    public function getAddressNumber()
    {
        return $this->addressNumber;
    }

    /**
     * Set addressRoad1.
     *
     * @param string $addressRoad1
     *
     * @return Customer
     */
    public function setAddressRoad1($addressRoad1)
    {
        $this->addressRoad1 = $addressRoad1;

        return $this;
    }

    /**
     * Get addressRoad1.
     *
     * @return string
     */
    public function getAddressRoad1()
    {
        return $this->addressRoad1;
    }

    /**
     * Set addressRoad2.
     *
     * @param string|null $addressRoad2
     *
     * @return Customer
     */
    public function setAddressRoad2($addressRoad2 = null)
    {
        $this->addressRoad2 = $addressRoad2;

        return $this;
    }

    /**
     * Get addressRoad2.
     *
     * @return string|null
     */
    public function getAddressRoad2()
    {
        return $this->addressRoad2;
    }

    /**
     * Set addressZipcode.
     *
     * @param int $addressZipcode
     *
     * @return Customer
     */
    public function setAddressZipcode($addressZipcode)
    {
        $this->addressZipcode = $addressZipcode;

        return $this;
    }

    /**
     * Get addressZipcode.
     *
     * @return int
     */
    public function getAddressZipcode()
    {
        return $this->addressZipcode;
    }

    /**
     * Set addressRegion.
     *
     * @param string|null $addressRegion
     *
     * @return Customer
     */
    public function setAddressRegion($addressRegion = null)
    {
        $this->addressRegion = $addressRegion;

        return $this;
    }

    /**
     * Get addressRegion.
     *
     * @return string|null
     */
    public function getAddressRegion()
    {
        return $this->addressRegion;
    }

    /**
     * Set addressCity.
     *
     * @param string $addressCity
     *
     * @return Customer
     */
    public function setAddressCity($addressCity)
    {
        $this->addressCity = $addressCity;

        return $this;
    }

    /**
     * Get addressCity.
     *
     * @return string
     */
    public function getAddressCity()
    {
        return $this->addressCity;
    }

    /**
     * Set addressCountry.
     *
     * @param string|null $addressCountry
     *
     * @return Customer
     */
    public function setAddressCountry($addressCountry = null)
    {
        $this->addressCountry = $addressCountry;

        return $this;
    }

    /**
     * Get addressCountry.
     *
     * @return string|null
     */
    public function getAddressCountry()
    {
        return $this->addressCountry;
    }

    /**
     * Set telephonePrimary.
     *
     * @param string $telephonePrimary
     *
     * @return Customer
     */
    public function setTelephonePrimary($telephonePrimary)
    {
        $this->telephonePrimary = $telephonePrimary;

        return $this;
    }

    /**
     * Get telephonePrimary.
     *
     * @return string
     */
    public function getTelephonePrimary()
    {
        return $this->telephonePrimary;
    }

    /**
     * Set telephoneSecondary.
     *
     * @param string $telephoneSecondary
     *
     * @return Customer
     */
    public function setTelephoneSecondary($telephoneSecondary)
    {
        $this->telephoneSecondary = $telephoneSecondary;

        return $this;
    }

    /**
     * Get telephoneSecondary.
     *
     * @return string
     */
    public function getTelephoneSecondary()
    {
        return $this->telephoneSecondary;
    }

    /**
     * Set lastActionDate.
     *
     * @param \DateTime $lastActionDate
     *
     * @return Customer
     */
    public function setLastActionDate($lastActionDate)
    {
        $this->lastActionDate = $lastActionDate;

        return $this;
    }

    /**
     * Get lastActionDate.
     *
     * @return \DateTime
     */
    public function getLastActionDate()
    {
        return $this->lastActionDate;
    }

    /**
     * Add vehicule.
     *
     * @param \AppBundle\Entity\Vehicule $vehicule
     *
     * @return Customer
     */
    public function addVehicule(\AppBundle\Entity\Vehicule $vehicule)
    {
        $this->vehicules[] = $vehicule;

        return $this;
    }

    /**
     * Remove vehicule.
     *
     * @param \AppBundle\Entity\Vehicule $vehicule
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeVehicule(\AppBundle\Entity\Vehicule $vehicule)
    {
        return $this->vehicules->removeElement($vehicule);
    }

    /**
     * Get vehicules.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVehicules()
    {
        return $this->vehicules;
    }

    /**
     * Add address.
     *
     * @param \AppBundle\Entity\Address_intervention $address
     *
     * @return Customer
     */
    public function addAddress(\AppBundle\Entity\Address_intervention $address)
    {
        $this->addresses[] = $address;

        return $this;
    }

    /**
     * Remove address.
     *
     * @param \AppBundle\Entity\Address_intervention $address
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeAddress(\AppBundle\Entity\Address_intervention $address)
    {
        return $this->addresses->removeElement($address);
    }

    /**
     * Get addresses.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAddresses()
    {
        return $this->addresses;
    }

    /**
     * Add command.
     *
     * @param \AppBundle\Entity\Command $command
     *
     * @return Customer
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
     * Set civility.
     *
     * @param string $civility
     *
     * @return Customer
     */
    public function setCivility($civility)
    {
        $this->civility = $civility;

        return $this;
    }

    /**
     * Get civility.
     *
     * @return string
     */
    public function getCivility()
    {
        return $this->civility;
    }
}
