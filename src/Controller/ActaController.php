<?php

namespace App\Controller;

use App\Entity\Acta;
use App\Entity\Archivos;
use App\Entity\Usuario;
use App\Repository\ActaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class ActaController extends AbstractController
{
    /**
     * @Route ("/api/acta", name="getActa", methods={"GET"})
     */
    public function getActa(): JsonResponse
    {
        $actaRe = $this->getDoctrine()->getRepository(Acta::class);
        $actas = $actaRe->findBy([], ["fecha"=>"DESC"]);
        $data = [];

        foreach ($actas as $acta) {
            $data[] = [
                "id" => $acta->getId(),
                "texto" => $acta->getTexto(),
                "fecha" => $acta->getFecha()->format("d/m/Y"),
                "archivo" => $acta->getArchivo()?$acta->getArchivo()->getId():null,
                "usuario" => $acta->getUsuario()->getId()
            ];
        }
        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route ("/api/acta/search/{search}", name="getActaSearch", methods={"GET"})
     */
    public function getActaSearch($search): JsonResponse
    {
        $actaRe = $this->getDoctrine()->getRepository(Acta::class);
        $actas = $actaRe->search($search);
        $data = [];

        foreach ($actas as $acta) {
            $data[] = [
                "id" => $acta->getId(),
                "texto" => $acta->getTexto(),
                "fecha" => $acta->getFecha()->format("d/m/Y"),
                "archivo" => $acta->getArchivo()?$acta->getArchivo()->getId():null,
                "usuario" => $acta->getUsuario()->getId()
            ];
        }
        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route ("/api/acta/id/{id}", name="getActaId", methods={"GET"})
     */
    public function getActaId($id): JsonResponse
    {
        $actaRe = $this->getDoctrine()->getRepository(Acta::class);
        $acta = $actaRe->findOneBy(["id" => $id]);
        $data = [];

        if(!empty($acta)) {
            $data = [
                "id" => $acta->getId(),
                "texto" => $acta->getTexto(),
                "fecha" => $acta->getFecha()->format("d/m/Y"),
                "archivo" => $acta->getArchivo()?$acta->getArchivo()->getId():null,
                "usuario" => $acta->getUsuario()->getId()
            ];
        }
        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/api/add/acta", name="add_acta", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $texto = $data['texto'];
        $fecha = new \DateTime($data['fecha']);
        $usuarioRe = $this->getDoctrine()->getRepository(Usuario::class);
        $usuario = $usuarioRe->find($data['usuario']);
        $archivoRe = $this->getDoctrine()->getRepository(Archivos::class);
        $archivo = $data['archivo']?$archivoRe->find($data['archivo']):null;

        if (empty($texto) || empty($fecha) || empty($usuario)) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        $actaRe = $this->getDoctrine()->getRepository(Acta::class);
        $actaRe->saveActa($texto, $fecha, $usuario, $archivo);

        return new JsonResponse(['status' => 'Acta creada!'], Response::HTTP_CREATED);
    }

    /**
     * @Route ("/api/acta/{id}", name="delete_acta", methods={"DELETE"})
     */
    public function delete($id) {
        $actaRe = $this->getDoctrine()->getRepository(Acta::class);
        $actaRe->removeActa($actaRe->findOneBy(["id" => $id]));

        return new JsonResponse(['status' => 'Acta borrada!'], Response::HTTP_CREATED);
    }

    /**
     * @param $id
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     * @Route ("/api/acta/{id}", name="put_acta", methods={"PUT"})
     */
    public function update($id, Request $request) {
        $actaRe = $this->getDoctrine()->getRepository(Acta::class);
        $acta = $actaRe->findOneBy(["id" => $id]);

        $data = json_decode($request->getContent(), true);

        empty($data['texto']) ? true : $acta->setTexto($data['texto']);
        empty($data['fecha']) ? true : $acta->setFecha(new \DateTime($data['fecha']));
        $usuarioRes = $this->getDoctrine()->getRepository(Usuario::class);
        empty($data['usuario']) ? true : $acta->setUsuario($usuarioRes->find($data['usuario']));
        $archivoRes = $this->getDoctrine()->getRepository(Archivos::class);
        empty($data['archivo']) ? true : $acta->setArchivo($archivoRes->find($data['archivo']));

        $actaRe->updateActa($acta);

        return new JsonResponse(['status' => 'Acta actualizada!'], Response::HTTP_CREATED);
    }

}
