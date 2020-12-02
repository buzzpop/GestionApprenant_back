<?php


namespace App\DataPersister;


use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Competences;
use Doctrine\ORM\EntityManagerInterface;

class CompetenceDataPersister implements ContextAwareDataPersisterInterface
{
    private $entityManager;
    public  function __construct(EntityManagerInterface $entityManager){
        $this->entityManager= $entityManager;

    }


    public function supports($data, array $context = []): bool
    {
        // TODO: Implement supports() method.
        return $data instanceof Competences;
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
        foreach ($data->getNiveaux() as $niveau){
            $niveau->setIsArchived(true);
            $this->entityManager->persist($niveau);
        }
        foreach ($data->getGroupeCompetence() as $g){
          $data->removeGroupeCompetence($g);
            $this->entityManager->persist($g);
        }

        $this->entityManager->flush();

    }
}