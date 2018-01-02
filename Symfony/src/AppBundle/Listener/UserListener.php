<?php
namespace AppBundle\Listener;


use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class UserListener
{

    private $passwordEncoderService;
    private $doctrine;

    public function __construct(UserPasswordEncoder $passwordEncoder, ManagerRegistry $doctrine)
    {
        $this->passwordEncoderService = $passwordEncoder;
        $this->doctrine = $doctrine;
    }

    /*
     * les méthodes doivent strictement reprendre le nom de l'événement
     * paramètres :
     *  - instance de l'entité écoutée
     *  - le type de paramètre diffère selon l'événement écouté
     * */
    public function prePersist(User $entity, LifecycleEventArgs $args){
        // encodage du mot de passe
        $encodedPassword = $this->passwordEncoderService->encodePassword($entity, $entity->getPassword());

        // redéfinition du mot de passe
        $entity->setPassword($encodedPassword);

        // sélectionner un rôle
        $rcRole = $this->doctrine->getRepository(Role::class);
        $role = $rcRole->findOneBy([
            'name' => 'ROLE_ADMIN'
        ]);

        // assigner un rôle
        $entity->addRole($role);

        //dump($entity); exit;
    }

}















