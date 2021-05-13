<?php
namespace App\Controller;

use App\Entity\Role;
use App\Repository\ActaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class RoleController extends AbstractController
{
    /**
     * @Route ("/api/role", name="getRole", methods={"GET"})
     */
    public function getRole(): JsonResponse
    {
        $roleRe = $this->getDoctrine()->getRepository(Role::class);
        $roles = $roleRe->findAll();
        $data = [];

        foreach ($roles as $role) {
            $data[] = [
                "id" => $role->getId(),
                "name" => $role->getName(),
            ];
        }
        return new JsonResponse($data, Response::HTTP_OK);
    }


    /**
     * @Route("/api/add/role", name="add_role", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $name = $data['name'];

        if (empty($name)) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        $roleRe = $this->getDoctrine()->getRepository(Role::class);
        $roleRe->saveRole($name);

        return new JsonResponse(['status' => 'Role creado!'], Response::HTTP_CREATED);
    }

    /**
     * @Route ("/api/role/{id}", name="delete_role", methods={"DELETE"})
     */
    public function delete($id) {
        $roleRe = $this->getDoctrine()->getRepository(Role::class);
        $roleRe->removeRole($roleRe->findOneBy(["id" => $id]));

        return new JsonResponse(['status' => 'Role borrado!'], Response::HTTP_CREATED);
    }

    /**
     * @Route ("/api/role/{id}", name="update_acta", methods={"PUT"})
     */
    public function update($id, Request $request) {
        $roleRe = $this->getDoctrine()->getRepository(Role::class);
        $role = $roleRe->findOneBy(["id" => $id]);

        $data = json_decode($request->getContent(), true);

        empty($data['name']) ? true : $role->setName($data['name']);

        $roleRe->updateRole($role);

        return new JsonResponse(['status' => 'Role actualizado!'], Response::HTTP_CREATED);
    }

}