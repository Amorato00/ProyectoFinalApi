<?php

namespace App\Repository;

use App\Entity\Descuento;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Descuento|null find($id, $lockMode = null, $lockVersion = null)
 * @method Descuento|null findOneBy(array $criteria, array $orderBy = null)
 * @method Descuento[]    findAll()
 * @method Descuento[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DescuentoRepository extends ServiceEntityRepository
{
    /**
     * @var EntityManagerInterface
     */
    //private EntityManagerInterface $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, Descuento::class);
        $this->manager = $manager;
    }

    public function saveDescuento($fechaInicio, $texto, $usuario, $fechaFin, $numDescuento, $titulo, $imagen) {
        $newDescuento = new Descuento();

        $newDescuento
            ->setFechaInicio($fechaInicio)
            ->setTexto($texto)
            ->setUsuario($usuario)
            ->setFechaFin($fechaFin)
            ->setImagen($imagen)
            ->setTitulo($titulo)
            ->setNumDescuento($numDescuento);

        $this->manager->persist($newDescuento);
        $this->manager->flush();
    }

    public function removeDescuento(Descuento $descuento) {
        $this->manager->remove($descuento);
        $this->manager->flush();
    }

    public function updateDescuento(Descuento $descuento) {
        $this->manager->persist($descuento);
        $this->manager->flush();

        return $descuento;
    }

    public function search(string $text): array
    {
        
        $qb = $this->createQueryBuilder('u')
            ->orWhere('u.titulo LIKE :value')
            ->orWhere('u.texto LIKE :value');
        $qb->setParameter('value', "%".$text."%");
        $qb->orderBy('u.fechaInicio', 'ASC');
        $query = $qb->getQuery();
        return $query->getResult();
    }
}
