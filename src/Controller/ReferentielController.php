<?php

namespace App\Controller;
use App\Repository\CompetencesRepository;
use App\Repository\ReferentielRepository;
use App\Services\ReferentielService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Referentiel;

class ReferentielController extends AbstractController
{

    /**
     * @Route(
     * name="postRef",
     * path="api/admin/referentiels",
     * methods={"POST"},
     * defaults={
     * "_controller"="\app\Controller\ReferentielController::postReferentiel",
     * "_api_resource_class"=Referentiel::class,
     * "_api_collection_operation_name"="postRef"
     * }
     * )
     */

    public function postReferentiel(ReferentielService $referentielService, Request $request){

          $referentiel=  $referentielService->postReferentiel($request);
            return $this->json($referentiel,Response::HTTP_CREATED);
    }


    /**
     * @Route(
     * name="getCompByGroupByRef",
     * path="api/admin/referentiels/{idR}/grpecompetences/{idG}",
     * methods={"GET"},
     * defaults={
     * "_controller"="\app\Controller\Referentiel::getCompetencesByGroupIdByRefId",
     * "_api_resource_class"=Referentiel::class,
     * "_api_collection_operation_name"="getCompByGroupByRef"
     * }
     * )
     */
    public function getCompetencesByGroupIdByRefId(int $idR,int $idG,CompetencesRepository $repository)
    {
        $competences= $repository->getCompetencesByGroupIdByRefId($idR,$idG);
        return $this->json($competences,Response::HTTP_OK);
    }

    /**
     * @Route(
     * name="putRef",
     * path="/api/admin/referentiels/{id}",
     * methods={"PUT"},
     * defaults={
     * "_controller"="\app\Controller\ReferentielController::putReferentiel",
     * "_api_resource_class"=Referentiel::class,
     * "_api_item_operation_name"="putRef"
     * }
     * )
     */
    public function putReferentiel(ReferentielService $service,Request $request, int $id){
      $referentiel=  $service->putReferentiel($request, $id);
        return $this->json($referentiel,Response::HTTP_OK);


    }

}
