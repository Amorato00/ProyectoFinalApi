<?php

namespace App\Repository;

use App\Entity\Estado;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Estado|null find($id, $lockMode = null, $lockVersion = null)
 * @method Estado|null findOneBy(array $criteria, array $orderBy = null)
 * @method Estado[]    findAll()
 * @method Estado[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EstadoRepository extends ServiceEntityRepository
{
    /**
     * @var EntityManagerInterface
     */
    //private EntityManagerInterface $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, Estado::class);
        $this->manager = $manager;
    }

    public function saveEstado($name) {
        $newEstado = new Estado();

        $newEstado
            ->setName($name);

        $this->manager->persist($newEstado);
        $this->manager->flush();
    }

    public function removeEstado(Estado $estado) {
        $this->manager->remove($estado);
        $this->manager->flush();
    }

    public function updateEstado(Estado $estado) {
        $this->manager->persist($estado);
        $this->manager->flush();

        return $estado;
    }
}
