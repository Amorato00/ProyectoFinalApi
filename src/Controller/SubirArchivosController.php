<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class SubirArchivosController
{
    /**
     * @Route ("/upload/img/perfil", name="postFotoPerfil", methods={"POST"})
     */
    public function uploadFotoPerfil(): JsonResponse
    {
        $archivo = $_FILES["archivo"];
        $resultado = move_uploaded_file($archivo["tmp_name"], "./img/fotoPerfil/".$archivo["name"]);
        if ($resultado) {
            return new JsonResponse("Subida con exito".$archivo["name"], Response::HTTP_OK);
        } else {
            return new JsonResponse("Subida fallida", Response::HTTP_OK);
        }

    }

    /**
     * @Route ("/upload/img", name="postImg", methods={"POST"})
     */
    public function uploadImg(): JsonResponse
    {
        $archivo = $_FILES["archivo"];
        $resultado = move_uploaded_file($archivo["tmp_name"], "./img/".$archivo["name"]);
        if ($resultado) {
            return new JsonResponse("Subida con exito".$archivo["name"], Response::HTTP_OK);
        } else {
            return new JsonResponse("Subida fallida", Response::HTTP_OK);
        }

    }

    /**
     * @Route ("/upload/file", name="postFile", methods={"POST"})
     */
    public function uploadFile(): JsonResponse
    {
        $archivo = $_FILES["archivo"];
        $resultado = move_uploaded_file($archivo["tmp_name"], "./files/".$archivo["name"]);
        if ($resultado) {
            return new JsonResponse("Subida con exito".$archivo["name"], Response::HTTP_OK);
        } else {
            return new JsonResponse("Subida fallida", Response::HTTP_OK);
        }

    }

    /**
     * @Route ("/file/{text}", name="getFile", methods={"GET"})
     */
    public function getFile($text): JsonResponse
    {
        $file = './files/'.$text;
        if(!file_exists($file)){ // file does not exist
            return new JsonResponse("Error al extraer archivo", Response::HTTP_OK);
            //die('file not found');
        } else {
            header ( 'Content-Description: File Transfer' ) ;
            header ( 'Content-Type: application/octet-stream' ) ;
            header ( 'Content-Disposition: attachment; filename="' . basename ( $file ) . '"' ) ;
            header ( 'Content-Length: ' . filesize ( $file ) ) ;
            header ( 'Pragma: public' ) ;

            // read the file from disk
            readfile($file);
            return new JsonResponse("Descarga de archivo", Response::HTTP_OK);
        }
    }

    /**
     * @Route ("/descargar/img/{text}", name="getImg", methods={"GET"})
     */
    public function getImg($text): JsonResponse
    {
        $file = './img/'.$text;
        if(!file_exists($file)){ // file does not exist
            return new JsonResponse("Error al extraer archivo", Response::HTTP_OK);
            //die('file not found');
        } else {
            header ( 'Content-Description: File Transfer' ) ;
            header ( 'Content-Type: application/octet-stream' ) ;
            header ( 'Content-Disposition: attachment; filename="' . basename ( $file ) . '"' ) ;
            header ( 'Content-Length: ' . filesize ( $file ) ) ;
            header ( 'Pragma: public' ) ;

            // read the file from disk
            readfile($file);
            return new JsonResponse("Descarga de archivo", Response::HTTP_OK);
        }
    }
}