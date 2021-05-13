<?php

namespace App\Controller;

use App\Entity\Estado;
use App\Repository\ActaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class EstadoController extends AbstractController
{
    /**
     * @Route ("/api/estado", name="getEstado", methods={"GET"})
     */
    public function getEstado(): JsonResponse
    {
        $estadoRe = $this->getDoctrine()->getRepository(Estado::class);
        $estados = $estadoRe->findAll();
        $data = [];

        foreach ($estados as $estado) {
            $data[] = [
                "id" => $estado->getId(),
                "name" => $estado->getName(),
            ];
        }
        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route ("/api/estado/{name}", name="getEstadoName", methods={"GET"})
     */
    public function getEstadoName($name): JsonResponse
    {
        $estadoRe = $this->getDoctrine()->getRepository(Estado::class);
        $estado = $estadoRe->findOneBy(["name" => $name]);
        $data = [];

        if(!empty($estado)) {
            $data = [
                "id" => $estado->getId(),
                "name" => $estado->getName(),
            ];
        }
        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/api/add/estado", name="add_estado", methods={"POST"})
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

        $estadoRe = $this->getDoctrine()->getRepository(Estado::class);
        $estadoRe->saveEstado($name);

        return new JsonResponse(['status' => 'Estado creado!'], Response::HTTP_CREATED);
    }

    /**
     * @Route ("/api/estado/{id}", name="delete_estado", methods={"DELETE"})
     */
    public function delete($id) {
        $estadoRe = $this->getDoctrine()->getRepository(Estado::class);
        $estadoRe->removeEstado($estadoRe->findOneBy(["id" => $id]));

        return new JsonResponse(['status' => 'Estado borrado!'], Response::HTTP_CREATED);
    }

    /**
     * @Route ("/api/estado/{id}", name="update_estado", methods={"PUT"})
     */
    public function update($id, Request $request) {
        $estadoRe = $this->getDoctrine()->getRepository(Estado::class);
        $estado = $estadoRe->findOneBy(["id" => $id]);

        $data = json_decode($request->getContent(), true);

        empty($data['name']) ? true : $estado->setName($data['name']);

        $estadoRe->updateEstado($estado);

        return new JsonResponse(['status' => 'Estado actualizado!'], Response::HTTP_CREATED);
    }
}
