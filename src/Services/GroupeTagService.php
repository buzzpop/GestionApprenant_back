<?php


namespace App\Services;


use App\Entity\GroupeTag;
use App\Entity\Tag;
use App\Repository\GroupeTagRepository;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

class GroupeTagService
{
    private $manager;
    private $serializer;
    private $tagRepository;
    private $error;
    private $grp_tagRepository;


    public function __construct( EntityManagerInterface $manager, SerializerInterface $serializer, TagRepository $tagRepository,ErrorService $errorService,GroupeTagRepository $grp_tagRepository){

        $this->manager=$manager;
        $this->serializer=$serializer;
        $this->tagRepository=$tagRepository;
        $this->error=$errorService;
        $this->grp_tagRepository=$grp_tagRepository;

    }

    public function addGrpTag(Request $request){
        $groupeTag=$request->getContent();
        $arrayGroupeTag= $this->serializer->decode($groupeTag,'json');


       if (!isset($arrayGroupeTag['tagss']) OR count($arrayGroupeTag['tagss'])==null){
           return false;
       }

       $groupeTagObject= $this->serializer->denormalize($arrayGroupeTag,GroupeTag::class,true);

       foreach ($arrayGroupeTag['tagss'] as $tag){

           $tagQuery= $this->tagRepository->findBy([
               "libelle"=>$tag['libelle']
           ]);


          if (!$tagQuery){
                $newTag= new Tag();
                $newTag->setLibelle($tag['libelle'])
                    ->setDescriptif($tag['description']);
                $this->manager->persist($newTag);
              $groupeTagObject->addTag($newTag);

          }else{

              $groupeTagObject->addTag($tagQuery[0]);
          }
       }
       $this->error->error($groupeTagObject);

       $this->manager->persist($groupeTagObject);
       $this->manager->flush();
        return true;
    }
// Modifier un nouveau groupe en modifiant le libelle, ou en y ajoutant des tags ou y supprimer

    public function  putGrpTag(Request $request , int  $id){
        $groupe=$request->getContent();
        $GroupeTag= $this->serializer->decode($groupe,'json');

        $groupeObj= $this->grp_tagRepository->find($id);

        if (isset($GroupeTag['tagss'])){
            foreach ($GroupeTag['tagss'] as $tag){

                if (isset($tag['libelle'])){
                    $requestTag= $this->tagRepository->findBy([
                        "libelle"=>$tag['libelle']
                    ]);


                    if (!$requestTag){
                        $newTag= new Tag();
                        $newTag->setLibelle($tag['libelle'])
                            ->setDescriptif($tag['description']);
                        $this->manager->persist($newTag);
                        $groupeObj->addTag($newTag);

                    }else{

                        $groupeObj->addTag($requestTag[0]);
                    }
                }
                if (isset($tag['id'])){
                    $requestTagId= $this->tagRepository->find(
                        $tag['id']
                    );
                    $groupeObj->removeTag($requestTagId);
                    $this->manager->persist($groupeObj);
                }
            }
        }
        $this->error->error($groupeObj);

        $this->manager->persist($groupeObj);
        $this->manager->flush();
        return true;

    }

}