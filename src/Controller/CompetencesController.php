<?php

namespace App\Controller;

use App\Services\CompetenceService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Competences;

class CompetencesController extends AbstractController
{

    /**
     * @Route(
     * name="post_competence",
     * path="/api/admin/competences",
     * methods={"POST"},
     * defaults={
     * "_controller"="\app\Controller\CompetencesController::post_competence",
     * "_api_resource_class"=Competences::class,
     * "_api_collection_operation_name"="post_competence"
     * }
     * )
     */

    public function post_competence(CompetenceService $competenceService,Request $request){
      if( $competenceService->postCompetence($request)){
          return $this->json("Success",200);
      }
        if( !$competenceService->postCompetence($request)){
            return $this->json("Ajouter 3 niveaux pour la competence",400);
        }
        return $this->json("Error",400);
    }
}
