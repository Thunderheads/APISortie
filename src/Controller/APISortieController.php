<?php

namespace App\Controller;


use App\Entity\Sortie;
use App\Repository\EtatRepository;
use App\Repository\LieuRepository;
use App\Repository\CampusRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ParticipantRepository;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class APISortieController extends AbstractController
{
    /**
     * @Route("/api/sortie/", name="api_sortie" , methods={"GET"})
     */
    public function sorties(SortieRepository $sortieRepo): Response
    {
        //$this->json($ville,200, [],['groups'=>'ville']);

        return $this->json($sortieRepo->findAll(),200, [],['groups'=>'sortie']);
    }

    /**
     * @Route("/api/sortie/{id}", name="api_sortie_detail" , methods={"GET"})
     */
    public function detailSortie(SortieRepository $sortieRepo, $id): Response
    {
        return $this->json($sortieRepo->find($id),200, [],['groups'=>'sortie']);
    }

    /**
     * @Route("/api/sortie/", name="api_sortie_ajouter", methods={"POST"})
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
        return $this->json($sortie,200, [],['groups'=>'sortie']); // avec id
    }

    /**
     * @Route("/api/sortie/{id}", name="api_sortie_modifier", methods={"PUT"})
     */
    public function modifier(Sortie $sortie, CampusRepository $campusRepo, EtatRepository $etatRepo, LieuRepository $lieuRepo, ParticipantRepository $participantRepo, Request $req,EntityManagerInterface $em): Response
    {
               
        // Récupération du body de la request
        $body = json_decode($req->getContent());
       
        // Prise en compte des mdoifications de la sortie dans l'objet $sortie
        $sortie->setNom($body->nom)
                ->setDateHeureDebut(new \DateTime($body->dateHeureDebut))
                ->setDuree(($body->duree))
                ->setDateLimiteInscription(new \DateTime($body->dateLimiteInscription))
                ->setNbInscriptionsMax($body->nbInscriptionsMax)
                ->setInfosSortie($body->infosSortie)
                ->setCampus($body->campus)
                ->setLieu($body->lieu);

        // Enregistrement en base de donnée
        $em->flush();

        // Return la sortie with the id
        return $this->json($sortie);
    }


    /**
     * @Route("/api/sortie/{id}", name="api_sortie_supprimer" , methods={"DELETE"})
     */
    public function supprimerSortie(EntityManagerInterface $em, Sortie $sortie): Response
    {
        // Remove la sortie en bdd
        $em->remove($sortie);
        $em->flush();

        // Retour utilisateur
        $tab['info'] = 'La sortie est supprimée';
        return $this->json($tab);
    }
}
