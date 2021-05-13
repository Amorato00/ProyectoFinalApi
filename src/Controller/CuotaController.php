<?php

namespace App\Controller;

use App\Entity\Cuota;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CuotaController extends AbstractController
{
    /**
     * @Route ("/api/cuota", name="getCuota", methods={"GET"})
     */
    public function getCuota(): JsonResponse
    {
        $cuotaRe = $this->getDoctrine()->getRepository(Cuota::class);
        $cuotas = $cuotaRe->findAll();
        $data = [];

        foreach ($cuotas as $cuota) {
            $data[] = [
                "id" => $cuota->getId(),
                "year" => $cuota->getYear(),
                "importe" => $cuota->getImporte()
            ];
        }
        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/api/add/cuota", name="add_archivo", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $year = $data['year'];
        $importe = $data['importe'];

        if (empty($year) || empty($importe)) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        $cuotaRe = $this->getDoctrine()->getRepository(Cuota::class);
        $cuotaRe->saveCuota($year, $importe);

        return new JsonResponse(['status' => 'Cuota guardada!'], Response::HTTP_CREATED);
    }

    /**
     * @Route ("/api/cuota/{id}", name="delete_cuota", methods={"DELETE"})
     */
    public function delete($id) {
        $cuotaRe = $this->getDoctrine()->getRepository(Cuota::class);
        $cuotaRe->removeCuota($cuotaRe->findOneBy(["id" => $id]));

        return new JsonResponse(['status' => 'Cuota borrada!'], Response::HTTP_CREATED);
    }

    /**
     * @Route ("/api/cuota/{id}", name="update_cuota", methods={"PUT"})
     */
    public function update($id, Request $request) {
        $cuotaRe = $this->getDoctrine()->getRepository(Cuota::class);
        $cuota = $cuotaRe->findOneBy(["id" => $id]);

        $data = json_decode($request->getContent(), true);

        empty($data['year']) ? true : $cuota->setYear($data['year']);
        empty($data['importe']) ? true : $cuota->setImporte($data['importe']);

        $cuotaRe->updateCuota($cuota);

        return new JsonResponse(['status' => 'Cuota actualizada!'], Response::HTTP_CREATED);
    }
}