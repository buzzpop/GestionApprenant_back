<?php

namespace App\Controller;

use App\Services\GroupeTagService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\GroupeTag;

class GroupeTagController extends AbstractController
{
    /**
     * @Route(
     * name="post_groupeTag",
     * path="/api/admin/grptags",
     * methods={"POST"},
     * defaults={
     * "_controller"="\app\Controller\GroupeTagController::addGrpTag",
     * "_api_resource_class"=GroupeTag::class,
     * "_api_collection_operation_name"="post_groupeTag"
     * }
     * )
     */
    public function addGrpTag(GroupeTagService $groupeTagService, Request $request){

       if (!$groupeTagService->addGrpTag($request)){
            return $this->json("Ajouter au moins un tag dans le nouveau Groupe",400);
        }

        return $this->json("Success",200);
    }

    /**
     * @Route(
     * name="put_grptag",
     * path="/api/admin/grptags/{id}",
     * methods={"PUT"},
     * defaults={
     * "_controller"="\app\Controller\GroupeTagController::putGrpTag",
     * "_api_resource_class"=GroupeTag::class,
     * "_api_item_operation_name"="put_grptag"
     * }
     * )
     */

    public function putGrpTag(GroupeTagService $groupeTagService, Request $request, int $id){
        if ($groupeTagService->putGrpTag($request, $id)){
            return $this->json("Success",200);
        }
        return $this->json("Error",400);

    }
}
