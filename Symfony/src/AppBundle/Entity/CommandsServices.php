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
     * @ORM\Column(name="qantity", type="integer")
     */
    private $qantity;

    /**
     * @var int
     *
     * @ORM\Column(name="value", type="integer")
     */
    private $value;

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
     * @param int $qantity
     * @param int $value
     */
    public function __construct()
    {
        $this->qantity = 1;

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
     * Set qantity.
     *
     * @param int $qantity
     *
     * @return CommandsServices
     */
    public function setQantity($qantity)
    {
        $this->qantity = $qantity;

        return $this;
    }

    /**
     * Get qantity.
     *
     * @return int
     */
    public function getQantity()
    {
        return $this->qantity;
    }

    /**
     * Set value.
     *
     * @param int $value
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
     * @return int
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
}