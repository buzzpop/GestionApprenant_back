<?php


namespace App\DataPersister;


use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\GroupeCompetences;
use Doctrine\ORM\EntityManagerInterface;

class GroupeCompetenceDataPersister implements ContextAwareDataPersisterInterface
{
    private $entityManager;
    public  function __construct(EntityManagerInterface $entityManager){
        $this->entityManager= $entityManager;

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
        if (isset($context['item_operation_name'])){
            $this->entityManager->persist($data);
            $this->entityManager->flush();
        }
    }

    public function remove($data, array $context = [])
    {
        // TODO: Implement remove() method.
        $data->setIsArchived(true);
        foreach ($data->getCompetences() as $competence){
            $data->removeCompetence($competence);
            $this->entityManager->persist($competence);
        }

        $this->entityManager->flush();

    }
}