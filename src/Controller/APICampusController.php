<?php

namespace App\Controller;

use App\Repository\CampusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class APICampusController extends AbstractController
{
    /**
     * @Route("/api/campus", name="qpp_campus", methods={"GET"})
     */
    public function getCampus(CampusRepository $campusRepo): Response
    {
        return $this->json($campusRepo->findAll(),200, [],['groups'=>'campus_all']);
    }
}
