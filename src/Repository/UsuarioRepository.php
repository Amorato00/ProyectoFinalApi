<?php

namespace App\Repository;

use App\Entity\Archivos;
use App\Entity\Usuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Usuario|null find($id, $lockMode = null, $lockVersion = null)
 * @method Usuario|null findOneBy(array $criteria, array $orderBy = null)
 * @method Usuario[]    findAll()
 * @method Usuario[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsuarioRepository extends ServiceEntityRepository
{
    /**
     * @var EntityManagerInterface
     */
   //private EntityManagerInterface $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, Usuario::class);
        $this->manager = $manager;
    }

    public function filterByText(string $text, array $filter = []): array
    {
        if(empty($filter)){
            $qb = $this->createQueryBuilder('u')
                ->orWhere('u.username LIKE :value')
                ->orWhere('u.nombre LIKE :value')
                ->orWhere('u.dni LIKE :value');
        } else {
            $key = key($filter);
            $qb = $this->createQueryBuilder('u')
                ->andWhere("u.$key = :filter")
                ->orWhere('u.username LIKE :value')
                ->orWhere('u.nombre LIKE :value')
                ->orWhere('u.dni LIKE :value');
            $qb->setParameter('filter', $filter[$key]);
        }

        $qb->setParameter('value', "%".$text."%");
        $qb->orderBy('u.id', 'DESC');
        $query = $qb->getQuery();
        return $query->getResult();
    }

    public function colaborador(): array
    {
        $qb = $this->createQueryBuilder('u')
            ->andWhere('u.role = 4');
        $query = $qb->getQuery();
        return $query->getResult();
    }

    public function colaboradorByText(): array
    {
        $qb = $this->createQueryBuilder('u')
            ->andWhere('u.role = 2');
        $query = $qb->getQuery();
        return $query->getResult();
    }

    public function searchByText(string $text, array $filter = []): array
    {
        if(empty($filter)){
            $qb = $this->createQueryBuilder('u')
                ->orWhere('u.username = :value')
                ->orWhere('u.email = :value');
        } else {
            $key = key($filter);
            $qb = $this->createQueryBuilder('u')
                ->andWhere("u.$key = :filter")
                ->orWhere('u.username = :value')
                ->orWhere('u.email = :value');
            $qb->setParameter('filter', $filter[$key]);
        }

        $qb->setParameter('value', $text);
        $qb->orderBy('u.id', 'DESC');
        $query = $qb->getQuery();
        return $query->getResult();
    }

    public function saveUsuario($username, $nombre, $apellidos, $password, $fecha_nacimiento
        , $email, $dni, $role, $estado, $foto, $telefono, $seccion, $iban, $direccion) {
        $newUsuario = new Usuario();

        $newUsuario
            ->setUsername($username)
            ->setNombre($nombre)
            ->setApellidos($apellidos)
            ->setPassword($password)
            ->setFechaNacimiento($fecha_nacimiento)
            ->setEmail($email)
            ->setDni($dni)
            ->setRole($role)
            ->setEstado($estado)
            ->setFotoPerfil($foto)
            ->setTelefono($telefono)
            ->setSeccion($seccion)
            ->setIban($iban)
            ->setDireccion($direccion);

        $this->manager->persist($newUsuario);
        $this->manager->flush();
    }

    public function removeUsuario(Usuario $usuario) {
        $this->manager->remove($usuario);
        $this->manager->flush();
    }

    public function updateUsuario(Usuario $usuario) {
        $this->manager->persist($usuario);
        $this->manager->flush();

        return $usuario;
    }

    public function searchSocio(string $text): array
    {
        
        $qb = $this->createQueryBuilder('u')
            ->andWhere('u.role = 1')
            ->orWhere('u.nombre LIKE :value')
            ->orWhere('u.dni LIKE :value')
            ->orWhere('u.apellidos LIKE :value');
        $qb->setParameter('value', "%".$text."%");
        $qb->orderBy('u.nombre', 'ASC');
        $query = $qb->getQuery();
        return $query->getResult();
    }

}
