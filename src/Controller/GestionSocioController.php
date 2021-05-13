<?php


namespace App\Controller;


use App\Entity\Cuota;
use App\Entity\GestionSocio;
use App\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class GestionSocioController extends AbstractController
{
    /**
     * @Route ("/api/gestion-socio", name="getGestionSocio", methods={"GET"})
     */
    public function getGestionSocio(): JsonResponse
    {
        $gestionSocioRe = $this->getDoctrine()->getRepository(GestionSocio::class);
        $gestionSocios = $gestionSocioRe->findAll();
        $data = [];

        foreach ($gestionSocios as $gestionSocio) {
            $data[] = [
                "id" => $gestionSocio->getId(),
                "fecha" => $gestionSocio->getFecha()->format("d/m/Y"),
                "importe" => $gestionSocio->getImporte(),
                "forma_pago" => $gestionSocio->getFormaPago(),
                "usuario" => $gestionSocio->getUsuario()->getId(),
                "cuota" => $gestionSocio->getCuota()->getId()
            ];
        }
        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route ("/api/gestion-socio/{id}", name="getGestionSocioIs", methods={"GET"})
     */
    public function getGestionSocioId($id): JsonResponse
    {
        $gestionSocioRe = $this->getDoctrine()->getRepository(GestionSocio::class);
        $gestionSocios = $gestionSocioRe->findBy(["usuario" => $id], ["fecha" => "DESC"]);
        $data = [];

        foreach ($gestionSocios as $gestionSocio) {
            $data[] = [
                "id" => $gestionSocio->getId(),
                "fecha" => $gestionSocio->getFecha()->format("d/m/Y"),
                "importe" => $gestionSocio->getImporte(),
                "forma_pago" => $gestionSocio->getFormaPago(),
                "usuario" => $gestionSocio->getUsuario()->getId(),
                "cuota" => $gestionSocio->getCuota()->getId()
            ];
        }
        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/api/add/gestion-socio", name="add_gestion_socio", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $fecha = new \DateTime($data['fecha']);
        $importe = $data['importe'];
        $forma_pago = $data['forma_pago'];
        $usuarioRe = $this->getDoctrine()->getRepository(Usuario::class);
        $usuario = $usuarioRe->find($data['usuario']);
        $cuotaRe = $this->getDoctrine()->getRepository(Cuota::class);
        $cuota = $cuotaRe->find($data['cuota']);

        if (empty($fecha) || empty($importe) || empty($forma_pago) || empty($usuario) || empty($cuota)) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        $gestionSocioRe = $this->getDoctrine()->getRepository(GestionSocio::class);
        $gestionSocioRe->saveGestionSocio($fecha, $usuario, $importe, $forma_pago, $cuota);

        return new JsonResponse(['status' => 'GestionSocio guardada!'], Response::HTTP_CREATED);
    }

    /**
     * @Route ("/api/gestion-socio/{id}", name="delete_gestion-socio", methods={"DELETE"})
     */
    public function delete($id) {
        $gestionSocioRe = $this->getDoctrine()->getRepository(GestionSocio::class);
        $gestionSocioRe->removeGestionSocio($gestionSocioRe->findOneBy(["id" => $id]));

        return new JsonResponse(['status' => 'GestionSocio borrada!'], Response::HTTP_CREATED);
    }

    /**
     * @Route ("/api/gestion-socio/{id}", name="update_gestion-socio", methods={"PUT"})
     */
    public function update($id, Request $request) {
        $gestionSocioRe = $this->getDoctrine()->getRepository(GestionSocio::class);
        $gestionSocio = $gestionSocioRe->findOneBy(["id" => $id]);

        $data = json_decode($request->getContent(), true);

        empty($data['fecha']) ? true : $gestionSocio->setFecha(new \DateTime($data['fecha']));
        empty($data['importe']) ? true : $gestionSocio->setImporte($data['importe']);
        empty($data['forma_pago']) ? true : $gestionSocio->setFormaPago($data['forma_pago']);
        $usuarioRe = $this->getDoctrine()->getRepository(Usuario::class);
        empty($data['usuario']) ? true : $gestionSocio->setUsuario($usuarioRe->findOneBy(["id" => $data['usuario']]));
        $cuotaRe = $this->getDoctrine()->getRepository(Cuota::class);
        empty($data['cuota']) ? true : $gestionSocio->setCuota($cuotaRe->findOneBy(["id" => $data['cuota']]));

        $gestionSocioRe->updateGestionSocio($gestionSocio);

        return new JsonResponse(['status' => 'Gestion Socio actualizada!'], Response::HTTP_CREATED);
    }
}