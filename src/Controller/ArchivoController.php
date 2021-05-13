<?php


namespace App\Controller;
use App\Entity\Archivos;
use App\Entity\Usuario;
use App\Repository\ActaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ArchivoController extends AbstractController
{
    /**
     * @Route ("/api/archivo", name="getArchivo", methods={"GET"})
     */
    public function getArchivo(): JsonResponse
    {
        $archivoRe = $this->getDoctrine()->getRepository(Archivos::class);
        $archivos = $archivoRe->findBy([], ["name" => "ASC"]);
        $data = [];

        foreach ($archivos as $archivo) {
            $data[] = [
                "id" => $archivo->getId(),
                "name" => $archivo->getName(),
                "usuario" => $archivo->getUsuario()->getId()
            ];
        }
        return new JsonResponse($data, Response::HTTP_OK);
    }

      /**
     * @Route ("/api/archivo/search/{text}", name="getArchivoSearch", methods={"GET"})
     */
    public function getArchivoSearch($text): JsonResponse
    {
        $archivoRe = $this->getDoctrine()->getRepository(Archivos::class);
        $archivos = $archivoRe->search($text);;
        $data = [];

        foreach ($archivos as $archivo) {
            $data[] = [
                "id" => $archivo->getId(),
                "name" => $archivo->getName(),
                "usuario" => $archivo->getUsuario()->getId()
            ];
        }
        return new JsonResponse($data, Response::HTTP_OK);
    }


    /**
     * @Route("/api/add/archivo", name="add_archivo", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $name = $data['name'];
        $usuarioRe = $this->getDoctrine()->getRepository(Usuario::class);
        $usuario = $usuarioRe->findOneBy(["id" => $data['usuario']]);

        if (empty($name) || empty($usuario)) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        $archivoRe = $this->getDoctrine()->getRepository(Archivos::class);
        $archivoRe->saveArchivo($name, $usuario);

        return new JsonResponse(['status' => 'Archivo guardado!'], Response::HTTP_CREATED);
    }

    /**
     * @Route ("/api/archivo/{id}", name="delete_archivo", methods={"DELETE"})
     */
    public function delete($id) {
        $archivoRe = $this->getDoctrine()->getRepository(Archivos::class);
        $archivoRe->removeArchivo($archivoRe->findOneBy(["id" => $id]));

        return new JsonResponse(['status' => 'Archivo borrado!'], Response::HTTP_CREATED);
    }

    /**
     * @Route ("/api/archivo/{id}", name="update_archivo", methods={"PUT"})
     */
    public function update($id, Request $request) {
        $archivoRe = $this->getDoctrine()->getRepository(Archivos::class);
        $archivo = $archivoRe->findOneBy(["id" => $id]);

        $data = json_decode($request->getContent(), true);

        empty($data['name']) ? true : $archivo->setName($data['name']);
        $usuarioRe = $this->getDoctrine()->getRepository(Usuario::class);
        empty($data['usuario']) ? true : $archivo->setUsuario($usuarioRe->findOneBy(["id" => $data['usuario']]));

        $archivoRe->updateArchivo($archivo);

        return new JsonResponse(['status' => 'Archivo actualizado!'], Response::HTTP_CREATED);
    }
}
