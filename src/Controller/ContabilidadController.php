<?php


namespace App\Controller;


use App\Entity\Contabilidad;
use App\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ContabilidadController extends AbstractController
{
    /**
     * @Route ("/api/contabilidad", name="getContabilidad", methods={"GET"})
     */
    public function getContabilidad(): JsonResponse
    {
        $contabilidadRe = $this->getDoctrine()->getRepository(Contabilidad::class);
        $contabilidades = $contabilidadRe->findAll();
        $data = [];

        foreach ($contabilidades as $contabilidad) {
            $data[] = [
                "id" => $contabilidad->getId(),
                "fecha" => $contabilidad->getFecha()->format("d/m/Y"),
                "concepto" => $contabilidad->getConcepto(),
                "d_h" => $contabilidad->getDH(),
                "importe" => $contabilidad->getImporte(),
                "saldo" => $contabilidad->getSaldo(),
                "usuario" => $contabilidad->getUsuario()->getId()
            ];
        }
        return new JsonResponse($data, Response::HTTP_OK);
    }

       /**
     * @Route ("/api/contabilidad/search/{search}", name="getContabilidadUltimoSearch", methods={"GET"})
     */
    public function getContabilidadSearch($search): JsonResponse
    {
        $contabilidadRe = $this->getDoctrine()->getRepository(Contabilidad::class);
        $contabilidades = $contabilidadRe->filterByText($search);
        $data = [];

         foreach ($contabilidades as $contabilidad) {
            $data[] = [
                "id" => $contabilidad->getId(),
                "fecha" => $contabilidad->getFecha()->format("d/m/Y"),
                "concepto" => $contabilidad->getConcepto(),
                "d_h" => $contabilidad->getDH(),
                "importe" => $contabilidad->getImporte(),
                "saldo" => $contabilidad->getSaldo(),
                "usuario" => $contabilidad->getUsuario()->getId()
            ];
        }
   
        return new JsonResponse($data, Response::HTTP_OK);
    }

     /**
     * @Route ("/api/contabilidad/ultima/{fecha}", name="getContabilidadUltimoPorFecha", methods={"GET"})
     */
    public function getContabilidadUltimoPorFecha($fecha): JsonResponse
    {
        $contabilidadRe = $this->getDoctrine()->getRepository(Contabilidad::class);
        $contabilidades = $contabilidadRe->buscarUltimoConceptoFecha($fecha);
        $data = [];

        if(!empty($contabilidades)) {
            $data = [
                "id" => $contabilidades[0]->getId(),
                "fecha" => $contabilidades[0]->getFecha()->format("d/m/Y"),
                "concepto" => $contabilidades[0]->getConcepto(),
                "d_h" => $contabilidades[0]->getDH(),
                "importe" => $contabilidades[0]->getImporte(),
                "saldo" => $contabilidades[0]->getSaldo(),
                "usuario" => $contabilidades[0]->getUsuario()->getId()
            ];
           } 
   
        return new JsonResponse($data, Response::HTTP_OK);
    }

       /**
     * @Route ("/api/contabilidad/id/{id}", name="getContabilidadId", methods={"GET"})
     */
    public function getContabilidadId($id): JsonResponse
    {
        $contabilidadRe = $this->getDoctrine()->getRepository(Contabilidad::class);
        $contabilidad = $contabilidadRe->find($id);
        $data = [];

        $data = [
            "id" => $contabilidad->getId(),
            "fecha" => $contabilidad->getFecha()->format("d/m/Y"),
            "concepto" => $contabilidad->getConcepto(),
            "d_h" => $contabilidad->getDH(),
            "importe" => $contabilidad->getImporte(),
            "saldo" => $contabilidad->getSaldo(),
            "usuario" => $contabilidad->getUsuario()->getId()
        ];
        
        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route ("/api/contabilidad/years", name="getContabilidadPorYears", methods={"GET"})
     */
    public function getContabilidadYears(): JsonResponse
    {
        $contabilidadRe = $this->getDoctrine()->getRepository(Contabilidad::class);
        $contabilidad = $contabilidadRe->filterYears();
        $data = [];

        foreach ($contabilidad as $years) {
            foreach ($years as $year) {
                $data[] = $year;
               }
        }

        
        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route ("/api/contabilidad/{fecha}", name="getContabilidadPorFecha", methods={"GET"})
     */
    public function getContabilidadPorFecha($fecha): JsonResponse
    {
        $contabilidadRe = $this->getDoctrine()->getRepository(Contabilidad::class);
        $contabilidades = $contabilidadRe->buscarPorFecha($fecha);
        $data = [];

        foreach ($contabilidades as $contabilidad) {
            $data[] = [
                "id" => $contabilidad->getId(),
                "fecha" => $contabilidad->getFecha()->format("d/m/Y"),
                "concepto" => $contabilidad->getConcepto(),
                "d_h" => $contabilidad->getDH(),
                "importe" => $contabilidad->getImporte(),
                "saldo" => $contabilidad->getSaldo(),
                "usuario" => $contabilidad->getUsuario()->getId()
            ];
        }
        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route ("/api/ultima/contabilidad", name="getContabilidadUltima", methods={"GET"})
     */
    public function getContabilidadUltima(): JsonResponse
    {
        $contabilidadRe = $this->getDoctrine()->getRepository(Contabilidad::class);
        $contabilidades = $contabilidadRe->findBy(array(), array("fecha"=>"DESC"),1,0,[0]);
        $data = [];

        if(!empty($contabilidades)) {
            $data = [
                "id" => $contabilidades[0]->getId(),
                "fecha" => $contabilidades[0]->getFecha()->format("d/m/Y"),
                "concepto" => $contabilidades[0]->getConcepto(),
                "d_h" => $contabilidades[0]->getDH(),
                "importe" => $contabilidades[0]->getImporte(),
                "saldo" => $contabilidades[0]->getSaldo(),
                "usuario" => $contabilidades[0]->getUsuario()->getId()
            ];
        }
        
        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/api/add/contabilidad", name="add_contabilidad", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $fecha = new \DateTime($data['fecha']);
        $concepto = $data['concepto'];
        $dh = $data['d_h'];
        $importe = $data['importe'];
        $saldo = $data['saldo'];
        $usuarioRe = $this->getDoctrine()->getRepository(Usuario::class);
        $usuario = $usuarioRe->find($data['usuario']);


        if (empty($fecha) || empty($concepto) || empty($dh) || empty($importe) || empty($saldo) || empty($usuario)) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        $contabilidadRe = $this->getDoctrine()->getRepository(Contabilidad::class);
        $contabilidadRe->saveContabilidad($importe, $fecha, $usuario, $concepto, $dh, $saldo);

        return new JsonResponse(['status' => 'Concepto guardado!'], Response::HTTP_CREATED);
    }

    /**
     * @Route ("/api/contabilidad/{id}", name="delete_contabilidad", methods={"DELETE"})
     */
    public function delete($id) {
        $contabilidadRe = $this->getDoctrine()->getRepository(Contabilidad::class);
        $contabilidadRe->removeContabilidad($contabilidadRe->findOneBy(["id" => $id]));

        return new JsonResponse(['status' => 'Concepto borrada!'], Response::HTTP_CREATED);
    }

    /**
     * @Route ("/api/contabilidad/{id}", name="update_contabilidad", methods={"PUT"})
     */
    public function update($id, Request $request) {
        $contabilidadRe = $this->getDoctrine()->getRepository(Contabilidad::class);
        $contabilidad = $contabilidadRe->findOneBy(["id" => $id]);

        $data = json_decode($request->getContent(), true);

        empty($data['fecha']) ? true : $contabilidad->setFecha(new \DateTime($data['fecha']));
        empty($data['concepto']) ? true : $contabilidad->setConcepto($data['concepto']);
        empty($data['d_h']) ? true : $contabilidad->setDH($data['d_h']);
        empty($data['importe']) ? true : $contabilidad->setImporte($data['importe']);
        empty($data['saldo']) ? true : $contabilidad->setSaldo($data['saldo']);
        $usuarioRe = $this->getDoctrine()->getRepository(Usuario::class);
        empty($data['usuario']) ? true : $contabilidad->setUsuario($usuarioRe->find($data['usuario']));

        $contabilidadRe->updateContabilidad($contabilidad);

        return new JsonResponse(['status' => 'Concepto actualizado!'], Response::HTTP_CREATED);
    }
}
