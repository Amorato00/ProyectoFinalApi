<?php

namespace App\Repository;

use App\Entity\Archivos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Archivos|null find($id, $lockMode = null, $lockVersion = null)
 * @method Archivos|null findOneBy(array $criteria, array $orderBy = null)
 * @method Archivos[]    findAll()
 * @method Archivos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArchivosRepository extends ServiceEntityRepository
{
    /**
     * @var EntityManagerInterface
     */
    //private EntityManagerInterface $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, Archivos::class);
        $this->manager = $manager;
    }

    public function saveArchivo($name, $usuario) {
        $newArchivo = new Archivos();

        $newArchivo
            ->setName($name)
            ->setUsuario($usuario);

        $this->manager->persist($newArchivo);
        $this->manager->flush();
    }

    public function removeArchivo(Archivos $archivo) {
        $this->manager->remove($archivo);
        $this->manager->flush();
    }

    public function updateArchivo(Archivos $archivos) {
        $this->manager->persist($archivos);
        $this->manager->flush();

        return $archivos;
    }

        public function search(string $text): array
    {
        
        $qb = $this->createQueryBuilder('u')
            ->orWhere('u.name LIKE :value');
        $qb->setParameter('value', "%".$text."%");
        $qb->orderBy('u.name', 'ASC');
        $query = $qb->getQuery();
        return $query->getResult();
    }
}
