<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ResultatAnnuel
 *
 * @ORM\Table(name="resultat_annuel")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ResultatAnnuelRepository")
 */
class ResultatAnnuel
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
     * @ORM\Column(name="nombreFactAcquitee", type="integer")
     */
    private $nombreFactAcquitee;

    /**
     * @var float
     *
     * @ORM\Column(name="HT", type="float")
     */
    private $ht;

    /**
     * @var float
     *
     * @ORM\Column(name="TVA", type="float")
     */
    private $tva;

    /**
     * @var float
     *
     * @ORM\Column(name="remise", type="float")
     */
    private $remise;

    /**
     * @var float
     *
     * @ORM\Column(name="TTC", type="float")
     */
    private $ttc;

    /**
     * Many resultatmensuel have One annee.
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Annee", inversedBy="resultatsAnnuels")
     * @ORM\JoinColumn(name="annee_id", referencedColumnName="id")
     */
    private $annee;


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
     * Set nombreFactAcquitee.
     *
     * @param int $nombreFactAcquitee
     *
     * @return ResultatAnnuel
     */
    public function setNombreFactAcquitee($nombreFactAcquitee)
    {
        $this->nombreFactAcquitee = $nombreFactAcquitee;

        return $this;
    }

    /**
     * Get nombreFactAcquitee.
     *
     * @return int
     */
    public function getNombreFactAcquitee()
    {
        return $this->nombreFactAcquitee;
    }

    /**
     * Set hT.
     *
     * @param float $hT
     *
     * @return ResultatAnnuel
     */
    public function setHT($ht)
    {
        $this->ht = $ht;

        return $this;
    }

    /**
     * Get ht.
     *
     * @return float
     */
    public function getHT()
    {
        return $this->ht;
    }

    /**
     * Set tva.
     *
     * @param float $tva
     *
     * @return ResultatAnnuel
     */
    public function setTVA($tva)
    {
        $this->tva = $tva;

        return $this;
    }

    /**
     * Get tva.
     *
     * @return float
     */
    public function getTVA()
    {
        return $this->tva;
    }

    /**
     * Set remise.
     *
     * @param float $remise
     *
     * @return ResultatAnnuel
     */
    public function setRemise($remise)
    {
        $this->remise = $remise;

        return $this;
    }

    /**
     * Get remise.
     *
     * @return float
     */
    public function getRemise()
    {
        return $this->remise;
    }

    /**
     * Set ttc.
     *
     * @param float $ttc
     *
     * @return ResultatAnnuel
     */
    public function setTTC($ttc)
    {
        $this->ttc = $ttc;

        return $this;
    }

    /**
     * Get ttc.
     *
     * @return float
     */
    public function getTTC()
    {
        return $this->ttc;
    }

    /**
     * Set annee.
     *
     * @param \AppBundle\Entity\Annee|null $annee
     *
     * @return ResultatAnnuel
     */
    public function setAnnee(\AppBundle\Entity\Annee $annee = null)
    {
        $this->annee = $annee;

        return $this;
    }

    /**
     * Get annee.
     *
     * @return \AppBundle\Entity\Annee|null
     */
    public function getAnnee()
    {
        return $this->annee;
    }
}
