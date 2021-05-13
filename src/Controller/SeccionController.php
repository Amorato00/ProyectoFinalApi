<?php


namespace App\Controller;


use App\Entity\Seccion;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
class SeccionController extends AbstractController
{
    /**
     * @Route ("/api/seccion", name="getSeccion", methods={"GET"})
     */
    public function getActa(): JsonResponse
    {
        $seccionRe = $this->getDoctrine()->getRepository(Seccion::class);
        $secciones = $seccionRe->findAll();
        $data = [];

        foreach ($secciones as $seccion) {
            $data[] = [
                "id" => $seccion->getId(),
                "name" => $seccion->getName(),
            ];
        }
        return new JsonResponse($data, Response::HTTP_OK);
    }
}