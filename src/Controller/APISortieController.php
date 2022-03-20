<?php

namespace App\Controller;


use App\Entity\Sortie;
use App\Repository\CampusRepository;
use App\Repository\EtatRepository;
use App\Repository\LieuRepository;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class APISortieController extends AbstractController
{
    /**
     * @Route("/api/sortie", name="api_sortie" , methods={"GET"})
     */
    public function sorties(SortieRepository $sortieRepo): Response
    {
        //$this->json($ville,200, [],['groups'=>'ville']);

        return $this->json($sortieRepo->findAll(),200, [],['groups'=>'sortie']);
    }

    /**
     * @Route("/api/sortie/", name="apie_sortie_ajouter", methods={"POST"})
     */
    public function ajouter(CampusRepository $campusRepo, EtatRepository $etatRepo, ParticipantRepository $participantRepo, LieuRepository $lieuRepo, Request $req,EntityManagerInterface $em): Response
    {
        $sortie = new Sortie();
        /*
         * TODO : modifier cette partie par la suite
         */
        $campus = $campusRepo->findOneBy(["nom"=>"Niort"]);
        $etat = $etatRepo->findOneBy(["libelle"=>"Créée"]);
        $organisateur = $participantRepo->find(11);
        $lieu = $lieuRepo->find(51);


        // pour récupérer le body $req->getContent()
        $sortieRequest = json_decode($req->getContent());


        $sortie->setNbInscriptionsMax(
            $sortieRequest->nbInscriptionsMax
        );
        $sortie->setNom(
            $sortieRequest->nom
        );
        $sortie->setDateHeureDebut(
            new \DateTime($sortieRequest->dateHeureDebut)
        );
        $sortie->setDateLimiteInscription(
            new \DateTime($sortieRequest->dateLimiteInscription)
        );
        $sortie->setDuree(
            $sortieRequest->duree
        );
        $sortie->setInfosSortie(
            $sortieRequest->infosSortie
        );


        /*
         * TODO : modifier ces données
         */
        $sortie->setLieu($lieu);
        $sortie->setOrganisateur($organisateur);
        $sortie->setCampus($campus);
        $sortie->setEtat($etat);



        $em->persist($sortie);
        $em->flush();
        return $this->json($sortie); // avec id
    }
}
