<?php


namespace App\Controller;

use App\Entity\Descuento;
use App\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class DescuentoController extends AbstractController
{
    /**
     * @Route ("/api/descuento", name="getDescuento", methods={"GET"})
     */
    public function getDescuento(): JsonResponse
    {
        $descuentoRe = $this->getDoctrine()->getRepository(Descuento::class);
        $descuentos = $descuentoRe->findBy([],["fechaInicio" => "ASC"]);
        $data = [];

        foreach ($descuentos as $descuento) {
            $data[] = [
                "id" => $descuento->getId(),
                "texto" => $descuento->getTexto(),
                "fechaInicio" => $descuento->getFechaInicio()->format("d/m/Y"),
                "fechaFin" => $descuento->getFechaFin()->format("d/m/Y"),
                "numDescuento" => $descuento->getNumDescuento(),
                "titulo" => $descuento->getTitulo(),
                "imagen" => $descuento->getImagen(),
                "usuario" => $descuento->getUsuario()->getId()
            ];
        }
        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route ("/api/descuento/search/{search}", name="getDescuentoSearch", methods={"GET"})
     */
    public function getDescuentoSearch($search): JsonResponse
    {
        $descuentoRe = $this->getDoctrine()->getRepository(Descuento::class);
        $descuentos = $descuentoRe->search($search);
        $data = [];

        foreach ($descuentos as $descuento) {
            $data[] = [
                "id" => $descuento->getId(),
                "texto" => $descuento->getTexto(),
                "fechaInicio" => $descuento->getFechaInicio()->format("d/m/Y"),
                "fechaFin" => $descuento->getFechaFin()->format("d/m/Y"),
                "numDescuento" => $descuento->getNumDescuento(),
                "titulo" => $descuento->getTitulo(),
                "imagen" => $descuento->getImagen(),
                "usuario" => $descuento->getUsuario()->getId()
            ];
        }
        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route ("/api/descuento/id/{id}", name="getDescuentoId", methods={"GET"})
     */
    public function getDescuentoId($id): JsonResponse
    {
        $descuentoRe = $this->getDoctrine()->getRepository(Descuento::class);
        $descuento = $descuentoRe->find($id);
        $data = [];

        if(!empty($descuento)) {
            $data = [
                "id" => $descuento->getId(),
                "texto" => $descuento->getTexto(),
                "fechaInicio" => $descuento->getFechaInicio()->format("d/m/Y"),
                "fechaFin" => $descuento->getFechaFin()->format("d/m/Y"),
                "numDescuento" => $descuento->getNumDescuento(),
                "titulo" => $descuento->getTitulo(),
                "imagen" => $descuento->getImagen(),
                "usuario" => $descuento->getUsuario()->getId()
            ];
        }
        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/api/add/descuento", name="add_descuento", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $texto = $data['texto'];
        $fechaInicio = new \DateTime($data['fechaInicio']);
        $fechaFin = new \DateTime($data['fechaFin']);
        $numDescuento = $data['numDescuento'];
        $usuarioRe = $this->getDoctrine()->getRepository(Usuario::class);
        $usuario = $usuarioRe->find($data['usuario']);
        $titulo = $data['titulo'];
        $imagen = $data['imagen'];

        if (empty($texto) || empty($fechaInicio) || empty($fechaFin) || empty($numDescuento) || empty($usuario)
            || empty($titulo) || empty($imagen)) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        $descuentoRe = $this->getDoctrine()->getRepository(Descuento::class);
        $descuentoRe->saveDescuento($fechaInicio, $texto, $usuario, $fechaFin, $numDescuento, $titulo, $imagen);

        return new JsonResponse(['status' => 'Descuento creado!'], Response::HTTP_CREATED);
    }


    /**
     * @Route ("/api/descuento/{id}", name="delete_descuento", methods={"DELETE"})
     */
    public function delete($id) {
        $descuentoRe = $this->getDoctrine()->getRepository(Descuento::class);
        $descuentoRe->removeDescuento($descuentoRe->findOneBy(["id" => $id]));

        return new JsonResponse(['status' => 'Descuento borrado!'], Response::HTTP_CREATED);
    }

    /**
     * @Route ("/api/descuento/{id}", name="put_descuento", methods={"PUT"})
     */
    public function update($id, Request $request) {
        $descuentoRe = $this->getDoctrine()->getRepository(Descuento::class);
        $descuento = $descuentoRe->findOneBy(["id" => $id]);

        $data = json_decode($request->getContent(), true);

        empty($data['texto']) ? true : $descuento->setTexto($data['texto']);
        empty($data['fechaInicio']) ? true : $descuento->setFechaInicio(new \DateTime($data['fechaInicio']));
        empty($data['fechaFin']) ? true : $descuento->setFechaFin(new \DateTime($data['fechaFin']));
        empty($data['numDescuento']) ? true : $descuento->setNumDescuento($data['numDescuento']);
        $usuarioRes = $this->getDoctrine()->getRepository(Usuario::class);
        empty($data['usuario']) ? true : $descuento->setUsuario($usuarioRes->find($data['usuario']));
        empty($data['titulo']) ? true : $descuento->setTitulo($data['titulo']);
        empty($data['imagen']) ? true : $descuento->setImagen($data['imagen']);

        $descuentoRe->updateDescuento($descuento);

        return new JsonResponse(['status' => 'Descuento actualizado!'], Response::HTTP_CREATED);
    }
}
