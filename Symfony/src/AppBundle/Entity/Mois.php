<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * mois
 *
 * @ORM\Table(name="mois")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MoisRepository")
 */
class Mois
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
     * @ORM\Column(name="code", type="integer", unique=true)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, unique=true)
     */
    private $name;

    /**
     * One mois has Many resultatMensuel.
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ResultatMensuel", mappedBy="mois", cascade={"persist"})
     */
    private $resultatsMensuels;


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
     * Set code.
     *
     * @param int $code
     *
     * @return mois
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code.
     *
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return mois
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
     * Constructor
     */
    public function __construct()
    {
        $this->resultatsMensuels = new \Doctrine\Common\Collections\ArrayCollection();
    }



    /**
     * Add resultatsMensuel.
     *
     * @param \AppBundle\Entity\ResultatMensuel $resultatsMensuel
     *
     * @return Mois
     */
    public function addResultatsMensuel(\AppBundle\Entity\ResultatMensuel $resultatsMensuel)
    {
        $this->resultatsMensuels[] = $resultatsMensuel;

        return $this;
    }

    /**
     * Remove resultatsMensuel.
     *
     * @param \AppBundle\Entity\ResultatMensuel $resultatsMensuel
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeResultatsMensuel(\AppBundle\Entity\ResultatMensuel $resultatsMensuel)
    {
        return $this->resultatsMensuels->removeElement($resultatsMensuel);
    }

    /**
     * Get resultatsMensuels.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getResultatsMensuels()
    {
        return $this->resultatsMensuels;
    }


}
