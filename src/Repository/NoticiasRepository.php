<?php

namespace App\Repository;

use App\Entity\Noticias;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Noticias|null find($id, $lockMode = null, $lockVersion = null)
 * @method Noticias|null findOneBy(array $criteria, array $orderBy = null)
 * @method Noticias[]    findAll()
 * @method Noticias[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NoticiasRepository extends ServiceEntityRepository
{
    //public EntityManagerInterface $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, Noticias::class);
        $this->manager = $manager;
    }

    public function order() {
        $qb = $this->createQueryBuilder('t')
            ->orderBy('t.fecha', 'DESC');

        return $qb->getQuery()->getResult();
    }

    public function saveNoticia($titulo, $texto, $fecha, $imagen, $usuario, $archivo) {
        $newNoticia = new Noticias();

        $newNoticia
            ->setTitulo($titulo)
            ->setTexto($texto)
            ->setFecha($fecha)
            ->setImagen($imagen)
            ->setUsuario($usuario)
            ->setArchivo($archivo);

        $this->manager->persist($newNoticia);
        $this->manager->flush();
    }

    public function removeNoticia(Noticias $noticias) {
        $this->manager->remove($noticias);
        $this->manager->flush();
    }

    public function updateNoticia(Noticias $noticias) {
        $this->manager->persist($noticias);
        $this->manager->flush();

        return $noticias;
    }

    public function search(string $text): array
    {
        
        $qb = $this->createQueryBuilder('u')
            ->orWhere('u.titulo LIKE :value')
            ->orWhere('u.texto LIKE :value');
        $qb->setParameter('value', "%".$text."%");
        $qb->orderBy('u.fecha', 'DESC');
        $query = $qb->getQuery();
        return $query->getResult();
    }
}
