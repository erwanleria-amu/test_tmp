<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 29/07/2017
 * Time: 15:58
 */

namespace AppBundle\Doctrine;


use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use DateTime;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserListener implements EventSubscriber
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @inheritdoc
     */
    public function getSubscribedEvents()
    {
        return ['prePersist', 'preUpdate'];
    }

    /**
     * Fonction intervenant avant la première persistence
     *
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args){
        $entity = $args->getEntity();

        if(!$entity instanceof User)
            return;

        $encoded = $this->passwordEncoder->encodePassword(
            $entity,
            $entity->getPlainPassword()
        );

        $entity->setPassword($encoded);
        $entity->setDateInscription(new DateTime());
        $entity->setAvatar('default.png');

        $baseRole = $args->getEntityManager()->getRepository('AppBundle:Role')
            ->findOneBy(['roleName' => 'Membre']);

        if($baseRole == null){
            $baseRole = new Role();
            $baseRole->setIsAdmin(0);
            $baseRole->setIsModerator(0);
            $baseRole->setRoleName('Membre');
            $baseRole->setRoleColor('#2E9AFE');
        }

        $entity->setRole($baseRole);
        $entity->setPrivateKey(md5(microtime(TRUE) * 100000));
    }

    /**
     * Fonction appelée avant la persistance des modifications
     *
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args){
        $entity = $args->getEntity();
        if(!$entity instanceof User)
            return null;
        $em = $args->getEntityManager();

        /**
         * On encode le mot de passe si plainPassword existe
         */
        if(null !== $entity->getPlainPassword())
            $this->encodePassword($entity);
        $meta = $em->getClassMetadata(get_class($entity));
        /**
         * force l'update
         */
        $em->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $entity);
    }


    /**
     * Fonction d'encodage de mot de passe
     *
     * @param User $entity
     */
    private function encodePassword(User $entity)
    {
        if (!$entity->getPlainPassword())
            return;

        $encoded = $this->passwordEncoder->encodePassword(
            $entity,
            $entity->getPlainPassword()
        );
        $entity->setPassword($encoded);
    }

}