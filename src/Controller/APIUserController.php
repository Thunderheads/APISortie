<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Repository\ParticipantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class APIUserController extends AbstractController
{
    /**
     * @Route("api/user/", name="app_a_p_i_user", methods={"GET"})
     */
    public function index(Request $req, ParticipantRepository $participantRepo): Response
    {
        // ces deux lignes de code permettent de récuperer deux objets présent dans l'url
        // Ces derniers caractérisent un participant, pour une question de sécurité,
        // il faudra peut etre crypté le mot de passe envoyé et le décrypté ici avant de jouer la requête
        $email = $req->query->get('email');
        $password = $req->query->get('password');

        $participant = $participantRepo->findOneBy(["mail"=> $email,"motPasse"=>$password ]);
        $participant;
        return $this->json($participant,200, [],['groups'=>'user']);

    }
}
