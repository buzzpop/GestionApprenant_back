<?php

namespace App\Controller;

use App\Services\ChatService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Chat;

class ChatController extends AbstractController
{
    /**
     * @Route(
     * name="chatGeneral",
     * path="/api/users/promo/{idp}/apprenant/{ida}/chats",
     * methods={"POST"},
     * defaults={
     * "_controller"="\app\Controller\ChatController::addChat",
     * "_api_resource_class"=Chat::class,
     * "_api_collection_operation_name"="chatGeneral",
     * }
     * )
     */
    public  function addChat(ChatService $chatService, Request $request, int $idp, int $ida){
       $chat= $chatService->addChat($request, $idp,$ida);

        return $this->json($chat,Response::HTTP_CREATED);
    }
}
