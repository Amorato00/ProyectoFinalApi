<?php

namespace App\Repository;

use App\Entity\Cuota;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Cuota|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cuota|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cuota[]    findAll()
 * @method Cuota[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CuotaRepository extends ServiceEntityRepository
{
    /**
     * @var EntityManagerInterface
     */
    //private EntityManagerInterface $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, Cuota::class);
        $this->manager = $manager;
    }

    public function saveCuota($year, $importe) {
        $newCuota = new Cuota();

        $newCuota
            ->setYear($year)
            ->setImporte($importe);

        $this->manager->persist($newCuota);
        $this->manager->flush();
    }

    public function removeCuota(Cuota $cuota) {
        $this->manager->remove($cuota);
        $this->manager->flush();
    }

    public function updateCuota(Cuota $cuota) {
        $this->manager->persist($cuota);
        $this->manager->flush();

        return $cuota;
    }
}
