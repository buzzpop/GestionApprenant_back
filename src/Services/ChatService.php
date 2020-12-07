<?php


namespace App\Services;


use App\Entity\Apprenant;
use App\Entity\Chat;
use App\Entity\Promo;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ChatService
{
    private $manager;
    private $error;
    private $serializer;
    public function __construct(EntityManagerInterface $entityManager, ErrorService $errorService, SerializerInterface $serializer){
        $this->manager=$entityManager;
        $this->error=$errorService;
        $this->serializer=$serializer;
   }

        public function addChat($request, $idp,$ida){
        $chat= $request->request->all();
        $jointe= $request->files->get('pieceJointes');
            $jointe= fopen($jointe,'rb');
            $promo= $this->manager->getRepository(Promo::class)->find($idp);
            $apprenant= $this->manager->getRepository(Apprenant::class)->find($ida);

            $chatObj= $this->serializer->denormalize($chat,Chat::class,'json');
            $chatObj->setPieceJointes($jointe);
            $chatObj->setUsers($apprenant);
            $chatObj->setPromo($promo);

            $this->error->error($chatObj);
             $this->manager->persist($chatObj);
             $this->manager->flush();

             return $chatObj;

        }
}