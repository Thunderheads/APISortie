<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Repository\LieuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class APILieuController extends AbstractController
{

    /**
     * Fonction en charge de recupérer un lieu
     *
     * @Route("api/lieux/", name="api_lieux", methods={"GET"})
     */
    public function lieuGetAll(LieuRepository $lieuRepo): Response
    {

        //toujours mettre cette ligne quand envoie des données cas si erreurs de references circulaires
        return $this->json($lieuRepo->findAll(),200, [],['groups'=>'lieu_get_all']);
    }

    /**
     * Fonction en charge de recupérer un lieu
     *
     * @Route("api/lieu/", name="api_lieu", methods={"GET"})
     */
    public function getLieu(Request $req, LieuRepository $lieuRepo): Response
    {
        $lieuReq = json_decode($req->getContent());
        $lieu = $lieuRepo->find($lieuReq->id);

        //toujours mettre cette ligne quand envoie des données cas si erreurs de references circulaires
        return $this->json($lieu,200, [],['groups'=>'lieu']);
    }



}
