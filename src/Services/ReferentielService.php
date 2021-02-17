<?php


namespace App\Services;


use App\Entity\Competences;
use App\Entity\GroupeCompetences;
use App\Entity\Niveau;
use App\Entity\Referentiel;
use App\Repository\GroupeCompetencesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

class ReferentielService
{
    private $manager;
    private $serializer;
    private $error;



    public function __construct( EntityManagerInterface $manager, SerializerInterface $serializer,ErrorService $errorService){

        $this->manager=$manager;
        $this->serializer=$serializer;
        $this->error=$errorService;
    }


    public function postReferentiel($request){

        $dataRef= $request->request->all();
        $programmeFile= $request->files->get("programme");
        $programmeFile= fopen($programmeFile->getRealPath(),'rb');
        $dataRefObj= $this->serializer->denormalize($dataRef,Referentiel::class,'json');
        $dataRefObj->setProgramme($programmeFile);
        foreach ($dataRef['grpeCompetence'] as $g){

             $groupeCompetence= $this->manager->getRepository(GroupeCompetences::class)->find($g);
            $dataRefObj->addGroupeCompetence($groupeCompetence);

        }

        $this->error->error($dataRefObj);

        $this->manager->persist($dataRefObj);
        $this->manager->flush();
        fclose($programmeFile);
        return $dataRefObj;

    }

    public function putReferentiel($request, int $id){
        $dataRef= $request->request->all();

        $programFile= $request->files->get("programme");

        if ($programFile){
            $programFile= fopen($programFile->getRealPath(),'rb');
        }

        $referentiel=$this->manager->getRepository(Referentiel::class)->find($id);


            isset($dataRef['libelle']) ? $referentiel->setLibelle($dataRef['libelle']) : true;
            isset($dataRef['presentation'])? $referentiel->setPresentation($dataRef['presentation']) : true;
            isset($dataRef['critereEvaluation'])? $referentiel->setCritereEvaluation($dataRef['critereEvaluation']) : true;
            isset($dataRef['critereAdmission'])? $referentiel->setCritereAdmission($dataRef['critereAdmission']) : true;

            if (isset($dataRef['grpeCompetence'])) {
            foreach ($dataRef['grpeCompetence'] as $key=>$item){
                $groupeCompetence= $this->manager->getRepository(GroupeCompetences::class)->find($item);
                if ($key=="delete"){

                  $referentiel->removeGroupeCompetence($groupeCompetence);
                }
               else{
                   $referentiel->addGroupeCompetence($groupeCompetence);
               }
            }

        }

        $referentiel->setProgramme($programFile);
            $this->manager->persist($referentiel);
            $this->manager->flush();
            if ($programFile){
                fclose($programFile);
            }

        return $referentiel;
    }

}
