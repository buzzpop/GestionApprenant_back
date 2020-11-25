<?php


namespace App\Services;


use App\Entity\User;
use App\Repository\ProfilRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserService
{
    private $manager;
    private $serializer;
    private $validator;
    private $encode;
    private $profilRepo;
    private $error;
    private $token;
    private $encoder;
    private $userRepo;

    public function __construct( EntityManagerInterface $manager, SerializerInterface $serializer, ValidatorInterface $validator,
                                 UserPasswordEncoderInterface $encode,ProfilRepository $profilRepository,ErrorService $errorService, TokenStorageInterface $tokenStorage,UserPasswordEncoderInterface $passwordEncoder,UserRepository $userRepository){

        $this->manager=$manager;
        $this->serializer=$serializer;
        $this->validator=$validator;
        $this->encode=$encode;
        $this->profilRepo=$profilRepository;
        $this->error=$errorService;
        $this->token=$tokenStorage;
        $this->encoder=$passwordEncoder;
        $this->userRepo=$userRepository;
    }
    public function addUser(Request $request){
        $dataUser= $request->request->all();
        $avatar= $request->files->get("avatar");
        $avatar= fopen($avatar->getRealPath(),'rb');
        $typeUser=$this->profilRepo->find( $dataUser['profils']);

        $profil=$typeUser->getLibelle();
        $profil=="Administrateur" ? $profil="User" : $profil=$typeUser->getLibelle();

        $userClass="App\Entity\\$profil";

        $userObject= $this->serializer->denormalize($dataUser,$userClass,true);

        $userObject->setProfil($typeUser);
        $password = $userObject->getPassword();
        $userObject -> setPassword($this->encode -> encodePassword($userObject, $password));
        $userObject-> setAvatar($avatar);

            $this->error->error($userObject);

        $this->manager->persist($userObject);
        $this->manager->flush();
        fclose($avatar);
        return new JsonResponse("l'Utilisateur a été ajouté avec succés",Response::HTTP_CREATED);

    }

    public function putUser(Request $request, int $id){
        $dataUser= $request->request->all();
        $avatar= $request->files->get("avatar");
        if ($avatar){
            $avatar= fopen($avatar->getRealPath(),'rb');
        }

        $typeUser=$this->userRepo->find($id);
        if ($typeUser){
            isset($dataUser['email']) ? $typeUser->setEmail($dataUser['email']) : true;
            isset($dataUser['password'])? $typeUser->setPassword($this->encoder->encodePassword($typeUser,$dataUser['password'])) : true;
            isset($dataUser['firstname'])? $typeUser->setFirstname($dataUser['firstname']) : true;
            isset($dataUser['lastname'])? $typeUser->setLastname($dataUser['lastname']) : true;
            isset($dataUser['address'])? $typeUser->setAddress($dataUser['address']) : true;
            isset($dataUser['tel'])? $typeUser->setTel($dataUser['tel']) : true;
            isset($dataUser['avatar']) ? $typeUser->setAvatar($avatar) : true;

            $this->manager->persist($typeUser);
            $this->manager->flush();
            return new JsonResponse("l'Utilisateur a été ajouté avec succés",Response::HTTP_CREATED);
        }

        if ($avatar){
            fclose($avatar);
        }

        return new JsonResponse("Error",Response::HTTP_BAD_REQUEST);
    }

}