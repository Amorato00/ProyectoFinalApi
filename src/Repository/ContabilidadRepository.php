<?php

namespace App\Repository;

use App\Entity\Contabilidad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Contabilidad|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contabilidad|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contabilidad[]    findAll()
 * @method Contabilidad[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContabilidadRepository extends ServiceEntityRepository
{
    //public EntityManagerInterface $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, Contabilidad::class);
        $this->manager = $manager;
    }

    public function saveContabilidad($importe, $fecha, $usuario, $concepto, $dh, $saldo) {
        $newContabilidad = new Contabilidad();

        $newContabilidad
            ->setUsuario($usuario)
            ->setImporte($importe)
            ->setFecha($fecha)
            ->setConcepto($concepto)
            ->setDH($dh)
            ->setSaldo($saldo);

        $this->manager->persist($newContabilidad);
        $this->manager->flush();
    }

    public function removeContabilidad(Contabilidad $contabilidad) {
        $this->manager->remove($contabilidad);
        $this->manager->flush();
    }

    public function updateContabilidad(Contabilidad $contabilidad) {
        $this->manager->persist($contabilidad);
        $this->manager->flush();

        return $contabilidad;
    }

    public function filterYears(): array
    {
        
        $qb = $this->createQueryBuilder('u')
            ->select("YEAR(u.fecha)");
        $qb->orderBy('u.fecha', 'DESC');
      
        $query = $qb
            ->distinct()
            ->getQuery();
        return $query->getResult();
    }


    public function filterByText(string $text): array
    {
        
        $qb = $this->createQueryBuilder('u')
            ->orWhere('u.concepto LIKE :value');
        $qb->setParameter('value', "%".$text."%");
        $qb->orderBy('u.fecha', 'DESC');
        $query = $qb->getQuery();
        return $query->getResult();
    }

    public function buscarPorFecha(string $text): array
    {
        $qb = $this->createQueryBuilder('u')
            ->andWhere("YEAR(u.fecha) = :fecha");
        $qb->setParameter('fecha', $text);
        $qb->orderBy('u.fecha', 'DESC');
        $query = $qb->getQuery();
        return $query->getResult();
    }

     public function buscarUltimoConceptoFecha(string $text): array
    {
        $qb = $this->createQueryBuilder('u')
            ->andWhere("YEAR(u.fecha) = :fecha");
        $qb->setParameter('fecha', $text);
        $qb->orderBy('u.fecha', 'DESC');
        $query = $qb->getQuery();
        return $query->getResult();
    }
}
