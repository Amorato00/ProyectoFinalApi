<?php

namespace App\Repository;

use App\Entity\Evento;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Evento|null find($id, $lockMode = null, $lockVersion = null)
 * @method Evento|null findOneBy(array $criteria, array $orderBy = null)
 * @method Evento[]    findAll()
 * @method Evento[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventoRepository extends ServiceEntityRepository
{
    /**
     * @var EntityManagerInterface
     */
    //private EntityManagerInterface $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, Evento::class);
        $this->manager = $manager;
    }

    public function order() {
        $qb = $this->createQueryBuilder('t')
            ->orderBy('t.fechaInicio', 'DESC');

        return $qb->getQuery()->getResult();
    }

    public function saveEvento($archivo, $texto, $fechaInicio, $fechaSubida, $imagen, $titulo, $usuario) {
        $newEvento = new Evento();

        $newEvento
            ->setArchivo($archivo)
            ->setTexto($texto)
            ->setFechaInicio($fechaInicio)
            ->setFechaSubida($fechaSubida)
            ->setImagen($imagen)
            ->setTitulo($titulo)
            ->setUser($usuario);

        $this->manager->persist($newEvento);
        $this->manager->flush();
    }

    public function removeEvento(Evento $evento) {
        $this->manager->remove($evento);
        $this->manager->flush();
    }

    public function updateEvento(Evento $evento) {
        $this->manager->persist($evento);
        $this->manager->flush();

        return $evento;
    }

    public function search(string $text): array
    {
        
        $qb = $this->createQueryBuilder('u')
            ->orWhere('u.titulo LIKE :value')
             ->orWhere('u.fechaInicio LIKE :value')
            ->orWhere('u.fechaSubida LIKE :value')
            ->orWhere('u.texto LIKE :value');
        $qb->setParameter('value', "%".$text."%");
        $qb->orderBy('u.fechaInicio', 'DESC');
        $query = $qb->getQuery();
        return $query->getResult();
    }
}
