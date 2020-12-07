<?php


namespace App\DataPersister;


use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Competences;
use App\Entity\Groupe;
use Doctrine\ORM\EntityManagerInterface;

class GroupeDataPersister implements ContextAwareDataPersisterInterface
{
    private $entityManager;
    public  function __construct(EntityManagerInterface $entityManager){
        $this->entityManager= $entityManager;

    }


    public function supports($data, array $context = []): bool
    {
        // TODO: Implement supports() method.
        return $data instanceof Groupe;
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
        foreach ($data->getApprenants() as $apprenant){
            $data->removeApprenant($apprenant);
        }
        foreach ($data->getFormateurs() as $formateur){
            $data->removeFormateur($formateur);
        }
        $promo=$data->getPromo();
        $promo->removeGroupe($data);

        $this->entityManager->flush();

    }

}