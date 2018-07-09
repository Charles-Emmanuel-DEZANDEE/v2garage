<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Annee
 *
 * @ORM\Table(name="annee")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AnneeRepository")
 */
class Annee
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
     * One anne has Many resultatMensuel.
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ResultatMensuel", mappedBy="annee", cascade={"persist"})
     */
    private $resultatsMensuels;

    /**
     * One annee has Many resultatMensuel.
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ResultatAnnuel", mappedBy="annee", cascade={"persist"})
     */
    private $resultatsAnnuels;


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
     * @return Annee
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
     * Constructor
     */
    public function __construct()
    {
        $this->resultatsMensuels = new \Doctrine\Common\Collections\ArrayCollection();
        $this->resultatsAnnuels = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add resultatsMensuel.
     *
     * @param \AppBundle\Entity\ResultatMensuel $resultatsMensuel
     *
     * @return Annee
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

    /**
     * Add resultatsAnnuel.
     *
     * @param \AppBundle\Entity\ResultatAnnuel $resultatsAnnuel
     *
     * @return Annee
     */
    public function addResultatsAnnuel(\AppBundle\Entity\ResultatAnnuel $resultatsAnnuel)
    {
        $this->resultatsAnnuels[] = $resultatsAnnuel;

        return $this;
    }

    /**
     * Remove resultatsAnnuel.
     *
     * @param \AppBundle\Entity\ResultatAnnuel $resultatsAnnuel
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeResultatsAnnuel(\AppBundle\Entity\ResultatAnnuel $resultatsAnnuel)
    {
        return $this->resultatsAnnuels->removeElement($resultatsAnnuel);
    }

    /**
     * Get resultatsAnnuels.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getResultatsAnnuels()
    {
        return $this->resultatsAnnuels;
    }
}
