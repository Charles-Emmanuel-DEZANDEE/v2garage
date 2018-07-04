<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CommandsServices
 *
 * @ORM\Table(name="commands_services")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CommandsServicesRepository")
 */
class CommandsServices
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
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

    /**
     * @var decimal
     *
     * @ORM\Column(name="value", type="decimal", precision=10, scale=2)
     */
    private $value;

    /**
     * valeur de la tva
     * @var decimal
     *
     * @ORM\Column(name="taxRate", type="decimal", precision=10, scale=2)
     */
    private $taxRate;

    /**
     * valeur de la remise
     * @var decimal
     *
     * @ORM\Column(name="discountRate", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $discountRate;

    /**
     * @var string|null
     *
     * @ORM\Column(name="reference", type="string", length=255, nullable=true)
     */
    private $reference;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Command", inversedBy="commandsServices")
     * @ORM\JoinColumn(name="command_id", referencedColumnName="id")
     */
    private $command;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Service", inversedBy="commandsServices")
     * @ORM\JoinColumn(name="service_id", referencedColumnName="id")
     */
    private $service;

    /**
     * CommandsServices constructor.
     * @param int $quantity
     * @param int $value
     */
    public function __construct(Service $service, Command $command)
    {
        $this->setQuantity(1);
        $this->setDiscountRate(0);
        $this->setService($service);
        $this->setCommand($command);
        $this->setTaxRate($service->getTaxRate()->getValue());
        $this->setValue($service->getValue());
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
     * retourne le calcul de la remise sur le prix ht
     * @return decimal
     * @author : Charles-emmanuel DEZANDEE <cdezandee@gmail.com>
     */
    public function getTotalDiscount(){
        return $this->getround($this->getValue() * ($this->getDiscountRate()/100));
    }

    /**
     * retourne le calcul de la tva
     * @return decimal
     * @author : Charles-emmanuel DEZANDEE <cdezandee@gmail.com>
     */
    public function getTotalTva(){
        return $this->getround(($this->getValue() - $this->getTotalDiscount()) * ($this->getTaxRate()/100));
    }

    /**
     * retourne le calcul du total ttc
     * @return decimal
     * @author : Charles-emmanuel DEZANDEE <cdezandee@gmail.com>
     */
    public function getTotalTtc(){
        return $this->getround($this->getValue() + $this->getTotalTva());
    }

    /**
     * @param $value
     * @return decimal
     * @author : Charles-emmanuel DEZANDEE <cdezandee@gmail.com>
     */
    private function getround($value){
        return round($value,2);
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
     * Set quantity.
     *
     * @param int $quantity
     *
     * @return CommandsServices
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity.
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set value.
     *
     * @param decimal $value
     *
     * @return CommandsServices
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
     * Set command.
     *
     * @param \AppBundle\Entity\Command|null $command
     *
     * @return CommandsServices
     */
    public function setCommand(\AppBundle\Entity\Command $command = null)
    {
        $this->command = $command;

        return $this;
    }

    /**
     * Get command.
     *
     * @return \AppBundle\Entity\Command|null
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * Set service.
     *
     * @param \AppBundle\Entity\Service|null $service
     *
     * @return CommandsServices
     */
    public function setService(\AppBundle\Entity\Service $service = null)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Get service.
     *
     * @return \AppBundle\Entity\Service|null
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Set taxRate.
     *
     * @param string $taxRate
     *
     * @return CommandsServices
     */
    public function setTaxRate($taxRate)
    {
        $this->taxRate = $taxRate;

        return $this;
    }

    /**
     * Get taxRate.
     *
     * @return string
     */
    public function getTaxRate()
    {
        return $this->taxRate;
    }

    /**
     * Set discountRate.
     *
     * @param string $discountRate
     *
     * @return CommandsServices
     */
    public function setDiscountRate($discountRate)
    {
        $this->discountRate = $discountRate;

        return $this;
    }

    /**
     * Get discountRate.
     *
     * @return string
     */
    public function getDiscountRate()
    {
        return $this->discountRate;
    }

    /**
     * Set reference.
     *
     * @param string|null $reference
     *
     * @return CommandsServices
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
}
