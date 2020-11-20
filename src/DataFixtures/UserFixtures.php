<?php

namespace App\DataFixtures;

use App\Entity\Apprenant;
use App\Entity\Cm;
use App\Entity\Formateur;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder= $encoder;

    }
    public function load(ObjectManager $manager)
    {
        $faker= Factory::create('en_EN');

        for ($p=0;$p<16;$p++) {
            if ($p<4){
                $user = new User();
               $user ->setProfil($this->getReference('Administrateur'));
            }
             elseif  ($p<8){
                $user = new Formateur();
                $user ->setProfil($this->getReference('Formateur'));
            }
             elseif  ($p<12){
                $user = new Apprenant();
                $user ->setProfil($this->getReference('Apprenant'));
            }
             else{
                $user = new Cm();
               $user  ->setProfil($this->getReference('Cm'));
            }

            $user->setFirstname($faker->firstName)
                ->setLastname($faker->lastName)
                ->setEmail($faker->email)
                ->setPassword($this->encoder->encodePassword($user, 'password'))
                ->setAddress($faker->address)
                ->setTel(770912122);
            $manager->persist($user);
        }


        $manager->flush();
    }

    public function getDependencies()
    {
        // TODO: Implement getDependencies() method.
        return array(
            ProfilFixtures::class
        );
    }

}

