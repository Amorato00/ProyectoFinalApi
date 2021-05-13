<?php


namespace App\Controller;


use App\Entity\Archivos;
use App\Entity\Evento;
use App\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class EventoController extends AbstractController
{
    /**
     * @Route ("/api/evento", name="getEvento", methods={"GET"})
     */
    public function getEvento(): JsonResponse
    {
        $eventoRe = $this->getDoctrine()->getRepository(Evento::class);
        $eventos = $eventoRe->order();
        $data = [];

        foreach ($eventos as $evento) {
            $data[] = [
                "id" => $evento->getId(),
                "titulo" => $evento->getTitulo(),
                "texto" => $evento->getTexto(),
                "fechaSubida" => $evento->getFechaSubida()->format("d/m/Y"),
                "fechaInicio" => $evento->getFechaInicio()->format("d/m/Y H:i:s"),
                "imagen" => $evento->getImagen(),
                "user" => $evento->getUser()->getId(),
                "archivo" => $evento->getArchivo()?$evento->getArchivo()->getId():null,
            ];
        }
        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route ("/api/evento/search/{search}", name="getEventoSearch", methods={"GET"})
     */
    public function getEventoSearch($search): JsonResponse
    {
        $eventoRe = $this->getDoctrine()->getRepository(Evento::class);
        $eventos = $eventoRe->search($search);
        $data = [];

        foreach ($eventos as $evento) {
            $data[] = [
                "id" => $evento->getId(),
                "titulo" => $evento->getTitulo(),
                "texto" => $evento->getTexto(),
                "fechaSubida" => $evento->getFechaSubida()->format("d/m/Y"),
                "fechaInicio" => $evento->getFechaInicio()->format("d/m/Y H:i:s"),
                "imagen" => $evento->getImagen(),
                "user" => $evento->getUser()->getId(),
                "archivo" => $evento->getArchivo()?$evento->getArchivo()->getId():null,
            ];
        }
        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route ("/api/evento/id/{id}", name="getEventoId", methods={"GET"})
     */
    public function getEventoId($id): JsonResponse
    {
        $eventoRe = $this->getDoctrine()->getRepository(Evento::class);
        $evento = $eventoRe->findOneBy(["id" => $id]);
        $data = [];

        $data = [
            "id" => $evento->getId(),
            "titulo" => $evento->getTitulo(),
            "texto" => $evento->getTexto(),
            "fechaSubida" => $evento->getFechaSubida()->format("d/m/Y"),
            "fechaInicio" => $evento->getFechaInicio()->format("d/m/Y H:i:s"),
            "imagen" => $evento->getImagen(),
            "user" => $evento->getUser()->getId(),
            "archivo" => $evento->getArchivo()?$evento->getArchivo()->getId():null,
        ];
        
        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/api/add/evento", name="add_evento", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $titulo = $data['titulo'];
        $texto = $data['texto'];
        $fechaSubida = new \DateTime($data['fechaSubida']);
var_dump($data['fechaSubida']);
        $fechaInicio = new \DateTime($data['fechaInicio']);
        $imagen = $data['imagen'];
        $usuarioRe = $this->getDoctrine()->getRepository(Usuario::class);
        $usuario = $usuarioRe->find($data['user']);
        $archivoRe = $this->getDoctrine()->getRepository(Archivos::class);
        $archivo = $data['archivo']?$archivoRe->find($data['archivo']):null;



        if (empty($titulo) || empty($texto) || empty($fechaInicio) || empty($fechaSubida) || empty($usuario)) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        $eventoRe = $this->getDoctrine()->getRepository(Evento::class);
        $eventoRe->saveEvento($archivo, $texto, $fechaInicio, $fechaSubida, $imagen, $titulo, $usuario);

        return new JsonResponse(['status' => 'Evento guardado!'], Response::HTTP_CREATED);
    }

    /**
     * @Route ("/api/evento/{id}", name="delete_vento", methods={"DELETE"})
     */
    public function delete($id) {
        $eventoRe = $this->getDoctrine()->getRepository(Evento::class);
        $eventoRe->removeEvento($eventoRe->findOneBy(["id" => $id]));

        return new JsonResponse(['status' => 'Evento borrado!'], Response::HTTP_CREATED);
    }

    /**
     * @Route ("/api/evento/{id}", name="update_evento", methods={"PUT"})
     */
    public function update($id, Request $request) {
        $eventoRe = $this->getDoctrine()->getRepository(Evento::class);
        $evento = $eventoRe->findOneBy(["id" => $id]);

        $data = json_decode($request->getContent(), true);

        empty($data['titulo']) ? true : $evento->setTitulo($data['titulo']);
        empty($data['texto']) ? true : $evento->setTexto($data['texto']);
        empty($data['fechaSubida']) ? true : $evento->setFechaSubida(new \DateTime($data['fechaSubida']));
        empty($data['fechaInicio']) ? true : $evento->setFechaInicio(new \DateTime($data['fechaInicio']));
        empty($data['imagen']) ? true : $evento->setImagen($data['imagen']);
        $usuarioRe = $this->getDoctrine()->getRepository(Usuario::class);
        empty($data['user']) ? true : $evento->setUser($usuarioRe->findOneBy(["id" => $data['user']]));
        $archivoRe = $this->getDoctrine()->getRepository(Archivos::class);
        empty($data['archivo']) ? true : $evento->setArchivo($archivoRe->findOneBy(["id" => $data['archivo']]));

        $eventoRe->updateEvento($evento);

        return new JsonResponse(['status' => 'Evento actualizado!'], Response::HTTP_CREATED);
    }
}
