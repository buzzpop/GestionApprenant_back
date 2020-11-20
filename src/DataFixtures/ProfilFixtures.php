<?php

namespace App\DataFixtures;

use App\Entity\Profil;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProfilFixtures extends Fixture
{
const tab=['Administrateur','Apprenant','Formateur','Cm'];


    public function load(ObjectManager $manager)
    {
        for ($p=0;$p<4;$p++){
            $profil= new Profil();
            $profil->setLibelle(self::tab[$p]);
            $this->addReference(self::tab[$p],$profil);
            $manager->persist($profil);
        }
        $manager->flush();
    }
}
