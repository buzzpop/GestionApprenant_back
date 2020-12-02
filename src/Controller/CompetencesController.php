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
