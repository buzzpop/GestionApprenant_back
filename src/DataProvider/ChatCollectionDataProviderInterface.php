<?php


namespace App\DataProvider;


use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use ApiPlatform\Core\Exception\ResourceClassNotSupportedException;
use App\Entity\Chat;
use App\Repository\ChatRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class ChatCollectionDataProviderInterface implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    private $request;
    private $repository;
    public function __construct(RequestStack $requestStack, ChatRepository $repository){
        $this->request=$requestStack;
        $this->repository=$repository;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        // TODO: Implement supports() method.
        return Chat::class === $resourceClass;
    }

    /**
     * @inheritDoc
     */
    public function getCollection(string $resourceClass, string $operationName = null, array $context = [])
    {
        // TODO: Implement getCollection() method.
       $uri=$this->request->getCurrentRequest();
      $idP= $uri->get("idp");
      $idA= $uri->get("ida");
      $data= $this->repository->getChatsByApprenant($idP,$idA);
      dd($uri);
    }


}