<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * PaymentType
 *
 * @ORM\Table(name="payment_type")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PaymentTypeRepository")
 */
class PaymentType
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
     * @ORM\Column(name="name", type="string", length=50, unique=true)
     */
    private $name;

    /**
     * One paymentType has Many commands.
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Command", mappedBy="paymentType", cascade={"persist"})
     */
    private $commands;

    public function __construct()
    {
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
     * @return PaymentType
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
     * Add command.
     *
     * @param \AppBundle\Entity\Command $command
     *
     * @return PaymentType
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
