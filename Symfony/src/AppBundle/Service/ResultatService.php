<?php

namespace AppBundle\Service;


use AppBundle\Entity\Annee;
use AppBundle\Entity\Command;
use AppBundle\Entity\Mois;
use AppBundle\Entity\ResultatAnnuel;
use AppBundle\Entity\ResultatMensuel;
use Doctrine\ORM\EntityManager;
use  Doctrine\ORM\EntityManagerInterface;

class ResultatService
{
    /**
     * @var EntityManager
     */
    protected $em;


    /**
     * ResultatService constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param null $annee
     * @param null $mois
     */
    public function updateResulat(Annee $annee = null, Mois $mois = null) : void
    {

        if ($annee == null) {
            $aujourdhui = new \DateTime();
            $annee = intval(date_format($aujourdhui, "Y"));
        }


        $rcCommande = $this->em->getRepository(Command::class);

        if ($mois == null) {

            $commands = $rcCommande->findFactureAcquitedByYear($annee);

            $this->updateResultatAnnuel($commands, $annee);
        } else {


            $commands = $rcCommande->findFactureAcquitedByYearAndMonth($annee, $mois);

            $this->updateResultatMensuel($commands, $annee, $mois);

        }


    }

    /**
     * met à jour l'entité resultat mensuel corespondant à l'année et le mois passé en parametre
     * @param Command $commands
     * @param int $annee
     */
    public function updateResultatAnnuel(array $commands, Annee $annee) : void
    {

        // on regarde si le resultat exist
        $rcResultAnnuel = $this->em->getRepository(ResultatAnnuel::class);
        $resultatExist = $rcResultAnnuel->findOneBy(['annee' => $annee]);
        $resultatAnnuel = $resultatExist ? $resultatExist : new ResultatAnnuel();

        // on set l'année si création d'un résultat
        if (!$resultatExist) {
            $resultatAnnuel->setAnnee($annee);
        }

        // on parcours les commandes
        $cumul = $this->getResultatByCommands($commands);

        //on set l'entité result annuelle
        $resultatAnnuel->setNombreFactAcquitee($cumul['nombreFact']);
        $resultatAnnuel->setHT($cumul['ht']);
        $resultatAnnuel->setRemise($cumul['remise']);
        $resultatAnnuel->setTVA($cumul['tva']);
        $resultatAnnuel->setTTC($cumul['ttc']);


        $this->em->persist($resultatAnnuel);
        $this->em->flush();


    }

    /**
     * met à jour l'entité resultat mensuel corespondant à l'année et le mois passé en parametre
     * @param $commands
     * @param int $annee
     * @param int $mois
     */
    public function updateResultatMensuel(array $commands, Annee $annee, Mois $mois) : void
    {

        // on regarde si le resultat exist
        $rcResultMensuel = $this->em->getRepository(ResultatMensuel::class);
        $resultatExist = $rcResultMensuel->findOneBy(['annee' => $annee, 'mois' => $mois]);
        $resultatMensuel = $resultatExist ? $resultatExist : new ResultatMensuel();

        // on set l'année et le mois si création d'un résultat
        if (!$resultatExist) {
            $resultatMensuel->setAnnee($annee);

            $resultatMensuel->setMois($mois);

        }

        // on parcours les commandes
        $cumul = $this->getResultatByCommands($commands);

        //on set l'entité result annuelle
        $resultatMensuel->setNombreFactAcquitee($cumul['nombreFact']);
        $resultatMensuel->setHT($cumul['ht']);
        $resultatMensuel->setRemise($cumul['remise']);
        $resultatMensuel->setTVA($cumul['tva']);
        $resultatMensuel->setTTC($cumul['ttc']);


        $this->em->persist($resultatMensuel);
        $this->em->flush();
    }

    /**
     * retourne un tableau avec les totaux de chaque attribut
     * @param Command $commands
     * @return array
     */
    public function getResultatByCommands (array $commands) : array {
        $cumul = ['nombreFact' => 0, 'ht' => 0, 'remise' => 0, 'tva' => 0, 'ttc' => 0];
        foreach ($commands as $command) {
            $cumul['nombreFact']++;
            $cumul['ht'] += $command->getTotalHt();
            $cumul['remise'] += $command->getTotalDiscount() ? $command->getTotalDiscount() : 0;
            $cumul['tva'] += $command->getTotalTva();
            $cumul['ttc'] += $command->getTotalTtc();
        }
        return $cumul;

    }

    public function anneeCheckOrCreate (int $annee) : Annee {
        $rcAnnee = $this->em->getRepository(Annee::class);

        $entiteAnnee = $rcAnnee->findOneBy(['code' => $annee]);
        if ($entiteAnnee == null) {
            $entiteAnnee = new Annee();
            $entiteAnnee->setCode($annee);

            $this->em->persist($entiteAnnee);
            $this->em->flush();
        }
        return $entiteAnnee;
    }

    public function createMois() : void {
        $table = [[1, 'Janvier'],[2, 'Février'],[3, 'Mars'],[4, 'Avril'],[5, 'Mai'],[6, 'Juin'],[7, 'Juillet'],[8, 'Août'],[9, 'Septembre'],[10, 'Octobre'],[11, 'Novembre'],[12, 'Décembre']];

        foreach ($table as $ligne){

            $mois = new Mois();
            $mois->setCode($ligne[0]);
            $mois->setName($ligne[1]);


            // On la persiste
            $this->em->persist($mois);
        }

        // On déclenche l'enregistrement
        $this->em->flush();
    }



}