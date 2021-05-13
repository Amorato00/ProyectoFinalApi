<?php

namespace App\Repository;
use App\Entity\Acta;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @method Acta|null find($id, $lockMode = null, $lockVersion = null)
 * @method Acta|null findOneBy(array $criteria, array $orderBy = null)
 * @method Acta[]    findAll()
 * @method Acta[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActaRepository extends ServiceEntityRepository
{
    /**
     * @var EntityManagerInterface
     */
    //public EntityManagerInterface $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, Acta::class);
        $this->manager = $manager;
    }

   public function saveActa($texto, $fecha, $usuario, $archivo) {
        $newActa = new Acta();

        $newActa
            ->setTexto($texto)
            ->setFecha($fecha)
            ->setUsuario($usuario)
            ->setArchivo($archivo);

        $this->manager->persist($newActa);
        $this->manager->flush();
   }

   public function removeActa(Acta $acta) {
        $this->manager->remove($acta);
        $this->manager->flush();
   }

   public function updateActa(Acta $acta) {
        $this->manager->persist($acta);
        $this->manager->flush();

        return $acta;
   }

    public function search(string $text): array
    {
        
        $qb = $this->createQueryBuilder('u')
            ->orWhere('u.texto LIKE :value');
        $qb->setParameter('value', "%".$text."%");
        $qb->orderBy('u.fecha', 'DESC');
        $query = $qb->getQuery();
        return $query->getResult();
    }
}
