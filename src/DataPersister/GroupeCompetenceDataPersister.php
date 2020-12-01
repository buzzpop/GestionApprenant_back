<?php


namespace App\DataPersister;


use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Competences;
use App\Entity\GroupeCompetences;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\SerializerInterface;


class GroupeCompetenceDataPersister implements ContextAwareDataPersisterInterface
{

    private $entityManager;
    private $request_stack;
    private $serializer;
    public  function __construct(EntityManagerInterface $entityManager,RequestStack $request_stack,SerializerInterface $serializer){
        $this->entityManager= $entityManager;
        $this->request_stack= $request_stack;
        $this->serializer= $serializer;
    }


    public function supports($data, array $context = []): bool
    {
        // TODO: Implement supports() method.
        return $data instanceof GroupeCompetences;
    }

    public function persist($data, array $context = [])
    {
        // TODO: Implement persist() method.
       if (isset($context['collection_operation_name'])){
           $this->entityManager->persist($data);
           $this->entityManager->flush();
       }

    if ($context['item_operation_name']){
        $req=$this->request_stack->getCurrentRequest();
       $content=json_decode($req->getContent(),true);

        $groupe= $this->entityManager->getRepository(GroupeCompetences::class)->find($req->get('id'));
       foreach ($content['competences'] as $competence){
           $competenceId= $this->entityManager->getRepository(Competences::class)->find($competence['id']);
           if (isset($competence['action']) and $competence['action']=="add"){
               $data->addCompetence($competenceId);
               $this->entityManager->persist($competenceId);
           }elseif (isset($competence['action']) and $competence['action']=="del"){
               $data->removeCompetence($competenceId);
               $this->entityManager->persist($competenceId);
           }

       }

    }
    $this->entityManager->flush();

    }

    public function remove($data, array $context = [])
    {
        // TODO: Implement remove() method.


    }
}