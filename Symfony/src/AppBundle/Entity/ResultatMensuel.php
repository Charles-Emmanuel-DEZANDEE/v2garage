<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ResultatMensuel
 *
 * @ORM\Table(name="resultat_mensuel")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ResultatMensuelRepository")
 */
class ResultatMensuel
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
     * Many resultatmensuel have One mois.
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Mois", inversedBy="resultatsMensuels")
     * @ORM\JoinColumn(name="mois_id", referencedColumnName="id")
     */
    private $mois;

    /**
     * Many resultatmensuel have One annee.
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Annee", inversedBy="resultatsMensuels")
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
     * @return ResultatMensuel
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
     * Set ht.
     *
     * @param int $ht
     *
     * @return ResultatMensuel
     */
    public function setHT($ht)
    {
        $this->ht = $ht;

        return $this;
    }

    /**
     * Get ht.
     *
     * @return int
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
     * @return ResultatMensuel
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
     * @return ResultatMensuel
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
     * @return ResultatMensuel
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
     * Set mois.
     *
     * @param \AppBundle\Entity\Mois|null $mois
     *
     * @return ResultatMensuel
     */
    public function setMois(\AppBundle\Entity\Mois $mois = null)
    {
        $this->mois = $mois;

        return $this;
    }

    /**
     * Get mois.
     *
     * @return \AppBundle\Entity\Mois|null
     */
    public function getMois()
    {
        return $this->mois;
    }

    /**
     * Set annee.
     *
     * @param \AppBundle\Entity\Annee|null $annee
     *
     * @return ResultatMensuel
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
