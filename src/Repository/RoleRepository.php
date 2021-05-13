<?php

namespace App\Repository;

use App\Entity\Role;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Role|null find($id, $lockMode = null, $lockVersion = null)
 * @method Role|null findOneBy(array $criteria, array $orderBy = null)
 * @method Role[]    findAll()
 * @method Role[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoleRepository extends ServiceEntityRepository
{
    /**
     * @var EntityManagerInterface
     */
    //private EntityManagerInterface $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, Role::class);
        $this->manager = $manager;
    }

    public function saveRole($name) {
        $newRole = new Role();

        $newRole
            ->setName($name);

        $this->manager->persist($newRole);
        $this->manager->flush();
    }

    public function removeRole(Role $role) {
        $this->manager->remove($role);
        $this->manager->flush();
    }

    public function updateRole(Role $role) {
        $this->manager->persist($role);
        $this->manager->flush();

        return $role;
    }
}
