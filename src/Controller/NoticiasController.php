<?php


namespace App\Controller;


use App\Entity\Archivos;
use App\Entity\Noticias;
use App\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class NoticiasController extends AbstractController
{
    /**
     * @Route ("/api/noticia", name="getNoticia", methods={"GET"})
     */
    public function getNoticia(): JsonResponse
    {
        $noticiaRe = $this->getDoctrine()->getRepository(Noticias::class);
        $noticias = $noticiaRe->order();
        $data = [];

        foreach ($noticias as $noticia) {
            $data[] = [
                "id" => $noticia->getId(),
                "titulo" => $noticia->getTitulo(),
                "texto" => $noticia->getTexto(),
                "fecha" => $noticia->getFecha()->format("d/m/Y"),
                "imagen" => $noticia->getImagen(),
                "usuario" => $noticia->getUsuario()->getId(),
                "archivo" => $noticia->getArchivo()?$noticia->getArchivo()->getId():null
            ];
        }
        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route ("/api/noticia/search/{search}", name="getNoticiaSearch", methods={"GET"})
     */
    public function getNoticiaSearch($search): JsonResponse
    {
        $noticiaRe = $this->getDoctrine()->getRepository(Noticias::class);
        $noticias = $noticiaRe->search($search);
        $data = [];

        foreach ($noticias as $noticia) {
            $data[] = [
                "id" => $noticia->getId(),
                "titulo" => $noticia->getTitulo(),
                "texto" => $noticia->getTexto(),
                "fecha" => $noticia->getFecha()->format("d/m/Y"),
                "imagen" => $noticia->getImagen(),
                "usuario" => $noticia->getUsuario()->getId(),
                "archivo" => $noticia->getArchivo()?$noticia->getArchivo()->getId():null
            ];
        }
        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route ("/api/noticia/id/{id}", name="getNoticiaId", methods={"GET"})
     */
    public function getNoticiaId($id): JsonResponse
    {
        $noticiaRe = $this->getDoctrine()->getRepository(Noticias::class);
        $noticia = $noticiaRe->findOneBy(["id" => $id]);
        $data = [];

        if(!empty($noticia)) {
            $data = [
                "id" => $noticia->getId(),
                "titulo" => $noticia->getTitulo(),
                "texto" => $noticia->getTexto(),
                "fecha" => $noticia->getFecha()->format("d/m/Y"),
                "imagen" => $noticia->getImagen(),
                "usuario" => $noticia->getUsuario()->getId(),
                "archivo" => $noticia->getArchivo()?$noticia->getArchivo()->getId():null
            ];
        }
        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/api/add/noticia", name="add_noticia", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $titulo = $data['titulo'];
        $texto = $data['texto'];
        $fecha = new \DateTime($data['fecha']);
        $imagen = $data['imagen'];
        $usuarioRe = $this->getDoctrine()->getRepository(Usuario::class);
        $usuario = $usuarioRe->find($data['usuario']);
        $archivoRe = $this->getDoctrine()->getRepository(Archivos::class);
        $archivo = $data['archivo']?$archivoRe->find($data['archivo']):null;

        if (empty($titulo) || empty($texto) || empty($fecha) || empty($imagen) || empty($usuario)) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        $noticiaRe = $this->getDoctrine()->getRepository(Noticias::class);
        $noticiaRe->saveNoticia($titulo, $texto, $fecha, $imagen, $usuario, $archivo);

        return new JsonResponse(['status' => 'Noticia creada!'], Response::HTTP_CREATED);
    }

    /**
     * @Route ("/api/noticia/{id}", name="deleteNoticia", methods={"DELETE"})
     */
    public function delete($id) {
        $noticiaRe = $this->getDoctrine()->getRepository(Noticias::class);
        $noticiaRe->removeNoticia($noticiaRe->findOneBy(["id" => $id]));

        return new JsonResponse(['status' => 'Noticia borrada!'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/api/noticia/{id}", name="update_descuento", methods={"PUT"})
     */
    public function update($id, Request $request) {
        $noticiasRe = $this->getDoctrine()->getRepository(Noticias::class);
        $noticia = $noticiasRe->findOneBy(["id" => $id]);

        $data = json_decode($request->getContent(), true);

        empty($data['titulo']) ? true : $noticia->setTitulo($data['titulo']);
        empty($data['texto']) ? true : $noticia->setTexto($data['texto']);
        empty($data['fecha']) ? true : $noticia->setFecha(new \DateTime($data['fecha']));
        empty($data['imagen']) ? true : $noticia->setImagen($data['imagen']);
        $usuarioRes = $this->getDoctrine()->getRepository(Usuario::class);
        empty($data['usuario']) ? true : $noticia->setUsuario($usuarioRes->find($data['usuario']));
        $archivoRe = $this->getDoctrine()->getRepository(Archivos::class);
        empty($data['archivo']) ? $noticia->setArchivo(null) : $noticia->setArchivo($archivoRe->find($data['archivo']));

        $noticiasRe->updateNoticia($noticia);

        return new JsonResponse(['status' => "Noticia actualizada! {$data['archivo']}"], Response::HTTP_CREATED);
    }
}
