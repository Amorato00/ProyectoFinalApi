<?php

namespace App\Repository;

use App\Entity\GestionSocio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GestionSocio|null find($id, $lockMode = null, $lockVersion = null)
 * @method GestionSocio|null findOneBy(array $criteria, array $orderBy = null)
 * @method GestionSocio[]    findAll()
 * @method GestionSocio[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GestionSocioRepository extends ServiceEntityRepository
{
    /**
     * @var EntityManagerInterface
     */
    //private EntityManagerInterface $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, GestionSocio::class);
        $this->manager = $manager;
    }

    public function saveGestionSocio($fecha, $usuario, $importe, $forma_pago, $cuota) {
        $newGestionSocio = new GestionSocio();

        $newGestionSocio
            ->setFecha($fecha)
            ->setUsuario($usuario)
            ->setImporte($importe)
            ->setFormaPago($forma_pago)
            ->setCuota($cuota);

        $this->manager->persist($newGestionSocio);
        $this->manager->flush();
    }

    public function removeGestionSocio(GestionSocio $gestionSocio) {
        $this->manager->remove($gestionSocio);
        $this->manager->flush();
    }

    public function updateGestionSocio(GestionSocio $gestionSocio) {
        $this->manager->persist($gestionSocio);
        $this->manager->flush();

        return $gestionSocio;
    }
}
