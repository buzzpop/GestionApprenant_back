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
      $competenceService->postCompetence($request);
    }
}
