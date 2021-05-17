<?php

namespace App\Controller;

use App\Entity\Estado;
use App\Entity\Role;
use App\Entity\Seccion;
use App\Entity\Usuario;
use App\Repository\ActaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class UsuarioController  extends AbstractController
{
    /**
     * API
     * @Route ("/api/usuario", name="getUsuario", methods={"GET"})
     */
    public function apiUsuarioGet(): JsonResponse
    {
        $usuarioRe = $this->getDoctrine()->getRepository(Usuario::class);
        $usuarios = $usuarioRe->findAll();
        $data = [];

        foreach ($usuarios as $usuario) {
            $data[] = [
                'id' => $usuario->getId(),
                'username' => $usuario->getUsername(),
                'nombre' => $usuario->getNombre(),
                'apellidos' => $usuario->getApellidos(),
                'fechaNacimiento' => $usuario->getFechaNacimiento()?$usuario->getFechaNacimiento()->format("Y-m-d"):null,
                'email' => $usuario->getEmail(),
                'dni' => $usuario->getDNI(),
                'telefono' => $usuario->getTelefono(),
                'fotoPerfil' => $usuario->getFotoPerfil(),
                'role' => $usuario->getRole()?$usuario->getRole()->getId():null,
                'estado' => $usuario->getEstado()?$usuario->getEstado()->getName():null,
                'seccion' => $usuario->getSeccion()?$usuario->getSeccion()->getName():null,
                'direccion' => $usuario->getDireccion()?$usuario->getDireccion():null,
                'iban' => $usuario->getIban()?$usuario->getIban():null,
            ];
        }
        return new JsonResponse($data, Response::HTTP_OK);
    }

  /**
     * API
     * @Route ("/api/colaborador", name="getColaborador", methods={"GET"})
     */
    public function apiColaboradorGet(): JsonResponse
    {
        $usuarioRe = $this->getDoctrine()->getRepository(Usuario::class);
        $usuarios = $usuarioRe->colaborador();
        $data = [];

        foreach ($usuarios as $usuario) {
            $data[] = [
                'id' => $usuario->getId(),
                'username' => $usuario->getUsername(),
                'nombre' => $usuario->getNombre(),
                'apellidos' => $usuario->getApellidos(),
                'fechaNacimiento' => $usuario->getFechaNacimiento()?$usuario->getFechaNacimiento()->format("Y-m-d"):null,
                'email' => $usuario->getEmail(),
                'dni' => $usuario->getDNI(),
                'telefono' => $usuario->getTelefono(),
                'fotoPerfil' => $usuario->getFotoPerfil(),
                'role' => $usuario->getRole()?$usuario->getRole()->getId():null,
                'estado' => $usuario->getEstado()?$usuario->getEstado()->getName():null,
                'seccion' => $usuario->getSeccion()?$usuario->getSeccion()->getName():null,
                'direccion' => $usuario->getDireccion()?$usuario->getDireccion():null,
                'iban' => $usuario->getIban()?$usuario->getIban():null,
            ];
        }
        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * API
     * @Route ("/api/usuario/search/socio/{search}", name="getUsuarioSearchSocio", methods={"GET"})
     */
    public function apiUsuarioGetSearchSocio($search): JsonResponse
    {
        $usuarioRe = $this->getDoctrine()->getRepository(Usuario::class);
        $usuarios = $usuarioRe->searchSocio($search);
        $data = [];

        foreach ($usuarios as $usuario) {
            $data[] = [
                'id' => $usuario->getId(),
                'username' => $usuario->getUsername(),
                'nombre' => $usuario->getNombre(),
                'apellidos' => $usuario->getApellidos(),
                'fechaNacimiento' => $usuario->getFechaNacimiento()?$usuario->getFechaNacimiento()->format("Y-m-d"):null,
                'email' => $usuario->getEmail(),
                'dni' => $usuario->getDNI(),
                'telefono' => $usuario->getTelefono(),
                'fotoPerfil' => $usuario->getFotoPerfil(),
                'role' => $usuario->getRole()?$usuario->getRole()->getId():null,
                'estado' => $usuario->getEstado()?$usuario->getEstado()->getName():null,
                'seccion' => $usuario->getSeccion()?$usuario->getSeccion()->getName():null,
                'direccion' => $usuario->getDireccion()?$usuario->getDireccion():null,
                'iban' => $usuario->getIban()?$usuario->getIban():null,
            ];
        }
        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * API
     * @Route ("/api/socio", name="getSocio", methods={"GET"})
     */

    public function apiSocio(): JsonResponse
    {
        $usuarioRe = $this->getDoctrine()->getRepository(Usuario::class);
        $usuarios = $usuarioRe->findBy(["role" => 2], ["nombre"=>"ASC"]);
        $data = [];
        foreach ($usuarios as $usuario) {
            $data[] = [
                'id' => $usuario->getid(),
                'username' => $usuario->getUsername(),
                'nombre' => $usuario->getNombre(),
                'apellidos' => $usuario->getApellidos(),
                'fechaNacimiento' => $usuario->getFechaNacimiento() ? $usuario->getFechaNacimiento()->format("Y-m-d") : null,
                'email' => $usuario->getEmail(),
                'dni' => $usuario->getDNI(),
                'telefono' => $usuario->getTelefono(),
                'fotoPerfil' => $usuario->getFotoPerfil(),
                'password' => $usuario->getPassword(),
                'role' => $usuario->getRole() ? $usuario->getRole()->getId() : null,
                'estado' => $usuario->getEstado() ? $usuario->getEstado()->getName() : null,
                'seccion' => $usuario->getSeccion()?$usuario->getSeccion()->getName():null,
                'direccion' => $usuario->getDireccion()?$usuario->getDireccion():null,
                'iban' => $usuario->getIban()?$usuario->getIban():null,
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * API
     * @Route ("/api/usuario/{id}", name="GetUsuarioId", methods={"GET"})
     */
    public function apiUsuarioGetId(int $id): JsonResponse
    {
        $usuarioRe = $this->getDoctrine()->getRepository(Usuario::class);
        $usuario = $usuarioRe->find($id);
        $data = [];

        $data[] = [
            'id' => $usuario->getid(),
            'username' => $usuario->getUsername(),
            'nombre' => $usuario->getNombre(),
            'apellidos' => $usuario->getApellidos(),
            'fechaNacimiento' => $usuario->getFechaNacimiento()?$usuario->getFechaNacimiento()->format("Y-m-d"):null,
            'email' => $usuario->getEmail(),
            'dni' => $usuario->getDNI(),
            'telefono' => $usuario->getTelefono(),
            'fotoPerfil' => $usuario->getFotoPerfil(),
            'password' => $usuario->getPassword(),
            'role' => $usuario->getRole()?$usuario->getRole()->getId():null,
            'estado' => $usuario->getEstado()?$usuario->getEstado()->getName():null,
            'seccion' => $usuario->getSeccion()?$usuario->getSeccion()->getName():null,
            'direccion' => $usuario->getDireccion()?$usuario->getDireccion():null,
            'iban' => $usuario->getIban()?$usuario->getIban():null,
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * API
     * @Route ("/api/usuario/search/{text}", name="GetUsuarioSearch", methods={"GET"})
     */
    public function apiUsuarioGetSearch(string $text): JsonResponse
    {
        $usuarioRe = $this->getDoctrine()->getRepository(Usuario::class);
        $usuarios = $usuarioRe->searchByText($text);
        $data = [];

        foreach ($usuarios as $usuario) {
            $data[] = [
                'id' => $usuario->getid(),
                'username' => $usuario->getUsername(),
                'nombre' => $usuario->getNombre(),
                'apellidos' => $usuario->getApellidos(),
                'fechaNacimiento' => $usuario->getFechaNacimiento()?$usuario->getFechaNacimiento()->format("Y-m-d"):null,
                'email' => $usuario->getEmail(),
                'dni' => $usuario->getDNI(),
                'telefono' => $usuario->getTelefono(),
                'fotoPerfil' => $usuario->getFotoPerfil(),
                'role' => $usuario->getRole()?$usuario->getRole()->getId():null,
                'estado' => $usuario->getEstado()?$usuario->getEstado()->getName():null,
                'password' => $usuario->getPassword(),
                'seccion' => $usuario->getSeccion()?$usuario->getSeccion()->getName():null,
                'direccion' => $usuario->getDireccion()?$usuario->getDireccion():null,
                'iban' => $usuario->getIban()?$usuario->getIban():null,
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * API
     * @Route ("/api/usuario/colaborador/{text}", name="GetUsuarioColaborador", methods={"GET"})
     */
    public function apiUsuarioGetColaborador(string $text): JsonResponse
    {
        $usuarioRe = $this->getDoctrine()->getRepository(Usuario::class);
        $usuarios = $usuarioRe->colaboradorByText();
        $data = [];
        $text = trim($text);
        foreach ($usuarios as $usuario) {
            if($usuario->getEmail() === $text || $usuario->getDNI() === $text || strval($usuario->getTelefono()) === $text) {
                $data[] = [
                    'id' => $usuario->getid(),
                    'username' => $usuario->getUsername(),
                    'nombre' => $usuario->getNombre(),
                    'apellidos' => $usuario->getApellidos(),
                    'telefono' => $usuario->getTelefono(),
                    'fechaNacimiento' => $usuario->getFechaNacimiento() ? $usuario->getFechaNacimiento()->format("Y-m-d") : null,
                    'email' => $usuario->getEmail(),
                    'dni' => $usuario->getDNI(),
                    'fotoPerfil' => $usuario->getFotoPerfil(),
                    'estado' => $usuario->getEstado() ? $usuario->getEstado()->getName() : null,
                    'seccion' => $usuario->getSeccion()?$usuario->getSeccion()->getName():null,
                    'direccion' => $usuario->getDireccion()?$usuario->getDireccion():null,
                    'iban' => $usuario->getIban()?$usuario->getIban():null,
                ];
            }
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * API
     * @Route ("/api/usuario/find/{text}", name="GetUsuarioFind", methods={"GET"})
     */
    public function apiUsuarioGetFind(string $text): JsonResponse
    {
        $usuarioRe = $this->getDoctrine()->getRepository(Usuario::class);
        $usuarios = $usuarioRe->filterByText($text);
        $data = [];

        foreach ($usuarios as $usuario) {
            $data[] = [
                'id' => $usuario->getid(),
                'username' => $usuario->getUsername(),
                'nombre' => $usuario->getNombre(),
                'apellidos' => $usuario->getApellidos(),
                'fechaNacimiento' => $usuario->getFechaNacimiento()?$usuario->getFechaNacimiento()->format("d/m/Y"):null,
                'email' => $usuario->getEmail(),
                'dni' => $usuario->getDNI(),
                'telefono' => $usuario->getTelefono(),
                'fotoPerfil' => $usuario->getFotoPerfil(),
                'password' => $usuario->getPassword(),
                'role' => $usuario->getRole()?$usuario->getRole()->getId():null,
                'estado' => $usuario->getEstado()?$usuario->getEstado()->getName():null,
                'seccion' => $usuario->getSeccion()?$usuario->getSeccion()->getName():null,
                'direccion' => $usuario->getDireccion()?$usuario->getDireccion():null,
                'iban' => $usuario->getIban()?$usuario->getIban():null,
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/api/add/usuario", name="add_usuario", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $username = $data['username'];
        $nombre = $data['nombre'];
        $apellidos = $data['apellidos'];
        $password = $data['password'];
        $fecha_nacimiento = new \DateTime($data['fechaNacimiento']);;
        $email = $data['email'];
        $dni = $data['dni']??null;
        $roleRe = $this->getDoctrine()->getRepository(Role::class);
        $role = $data['role']?$roleRe->find($data['role']):null;
        $estadoRe = $this->getDoctrine()->getRepository(Estado::class);
        $estado = $data['estado']?$estadoRe->find($data['estado']):null;
        $foto = $data['foto']??null;
        $telefono = $data['telefono']??null;
        $seccionRe = $this->getDoctrine()->getRepository(Seccion::class);
        $seccion = $data['seccion']?$seccionRe->find($data['seccion']):null;
        $iban = $data['iban'];
        $direccion = $data['direccion'];

        if (empty($username) || empty($nombre) || empty($apellidos) || empty($password) || empty($email)) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        $usuarioRe = $this->getDoctrine()->getRepository(Usuario::class);
        $usuarioRe->saveUsuario($username, $nombre, $apellidos, $password, $fecha_nacimiento
        , $email, $dni, $role, $estado, $foto, $telefono, $seccion, $iban, $direccion);

        return new JsonResponse(['status' => 'Usuario creado!'], Response::HTTP_CREATED);
    }

    /**
     * @Route ("/api/usuario/{id}", name="delete_usuario", methods={"DELETE"})
     */
    public function delete($id) {
        $usuarioRe = $this->getDoctrine()->getRepository(Usuario::class);
        $usuarioRe->removeUsuario($usuarioRe->findOneBy(["id" => $id]));

        return new JsonResponse(['status' => 'Usuario borrado!'], Response::HTTP_CREATED);
    }

    /**
     * @Route ("/api/usuario/{id}", name="update_usuario", methods={"PUT"})
     */
    public function update($id, Request $request) {
        $usuarioRe = $this->getDoctrine()->getRepository(Usuario::class);
        $usuario = $usuarioRe->findOneBy(["id" => $id]);

        $data = json_decode($request->getContent(), true);

        empty($data['username']) ? true : $usuario->setUsername($data['username']);
        empty($data['nombre']) ? true : $usuario->setNombre($data['nombre']);
        empty($data['apellidos']) ? true : $usuario->setApellidos($data['apellidos']);
        empty($data['password']) ? true : $usuario->setPassword($data['password']);
        empty($data['fechaNacimiento']) ? true : $usuario->setFechaNacimiento(new \DateTime($data['fechaNacimiento']));
        empty($data['email']) ? true : $usuario->setEmail($data['email']);
        empty($data['dni']) ? true : $usuario->setDni($data['dni']);
        $roleRe = $this->getDoctrine()->getRepository(Role::class);
        empty($data['role']) ? true : $usuario->setRole($roleRe->find($data['role']));
        $estadoRe = $this->getDoctrine()->getRepository(Estado::class);
        empty($data['estado']) ? true : $usuario->setEstado($estadoRe->find($data['estado']));
        empty($data['fotoPerfil']) ? true : $usuario->setFotoPerfil($data['fotoPerfil']);
        empty($data['telefono']) ? true : $usuario->setTelefono($data['telefono']);
        $seccionRe = $this->getDoctrine()->getRepository(Seccion::class);
        empty($data['seccion']) ? true : $usuario->setEstado($seccionRe->find($data['seccion']));
        empty($data['iban']) ? true : $usuario->setIban($data['iban']);
        empty($data['direccion']) ? true : $usuario->setDireccion($data['direccion']);

        $usuarioRe->updateUsuario($usuario);

        return new JsonResponse(['status' => 'Usuario actualizado!'], Response::HTTP_CREATED);
    }
}
