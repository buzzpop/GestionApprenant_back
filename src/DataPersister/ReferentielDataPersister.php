<?php


namespace App\DataPersister;


use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Referentiel;
use Doctrine\ORM\EntityManagerInterface;

class ReferentielDataPersister implements ContextAwareDataPersisterInterface
{
    private $entityManager;
    public  function __construct(EntityManagerInterface $entityManager){
        $this->entityManager= $entityManager;

    }


    public function supports($data, array $context = []): bool
    {
        // TODO: Implement supports() method.
        return $data instanceof Referentiel;
    }

    public function persist($data, array $context = [])
    {
        // TODO: Implement persist() method.
    }

    public function remove($data, array $context = [])
    {
        // TODO: Implement remove() method.

       $data->setIsArchived(true);

      foreach ($data->getGroupeCompetences() as $g){
         $data->removeGroupeCompetence($g);

      }

            $this->entityManager->flush();

    }
}