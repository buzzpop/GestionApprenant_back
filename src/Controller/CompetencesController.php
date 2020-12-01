<?php

namespace App\Controller;

use App\Services\CompetenceService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    /**
     * @Route(
     * name="put_competence",
     * path="/api/admin/competences/{id}",
     * methods={"PUT"},
     * defaults={
     * "_controller"="\app\Controller\CompetencesController::put_competence",
     * "_api_resource_class"=Competences::class,
     * "_api_item_operation_name"="put_competence",
     *   "_api_receive"=false
     * }
     * )
     */

    public function put_competence(CompetenceService $competenceService,Request $request,int $id){
        if (  $competenceService->putCompetence($request,$id)){
            return $this->json("success",200);
        }
        return $this->json("Error",400);

    }
}
