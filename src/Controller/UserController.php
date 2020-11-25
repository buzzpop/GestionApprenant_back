<?php

namespace App\Controller;

use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;

class UserController extends AbstractController
{
    private $userService;
    public  function  __construct(UserService $userService){
        $this->userService= $userService;
    }

    /**
     * @Route(
     * name="add_user",
     * path="/api/admin/users",
     * methods={"POST"},
     * defaults={
     * "_controller"="\app\Controller\UserController::addUser",
     * "_api_resource_class"=User::class,
     * "_api_collection_operation_name"="add_user"
     * }
     * )
     */
    public function addUser(Request $request)
    {
        $this->userService->addUser($request);

    }

    /**
     * @Route(
     * name="put_user",
     * path="/api/admin/users/{id}",
     * methods={"PUT"},
     * defaults={
     * "_controller"="\app\Controller\UserController::putUser",
     * "_api_resource_class"=User::class,
     * "_api_collection_operation_name"="put_user"
     * }
     * )
     */

    public function putUser(Request $request, int $id)
    {
        $this->userService->putUser($request, $id);

    }
}
