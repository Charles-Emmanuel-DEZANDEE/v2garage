<?php
/**
 * Created by PhpStorm.
 * User: cdezandee
 * Date: 09/10/2017
 * Time: 14:36
 */

namespace AppBundle\Command;

use AppBundle\Service\ResultatService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class CreateMonthCommand extends Command
{
    private $resulatService;

    public function __construct(ResultatService $resulatService)
    {
        $this->resulatService = $resulatService;

        parent::__construct();
    }


    protected function configure()
    {
        $this
            ->setName('createMois')
            ->setDescription('peuple la table mois de l\'application');

    }

    protected function execute (InputInterface $input, OutputInterface $output){


        // Récupération des services


        $this->resulatService->createMois();

        $output->write('Le peuplement de la table Mois est fait');


    }

}