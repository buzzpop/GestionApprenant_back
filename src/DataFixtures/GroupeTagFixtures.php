<?php

namespace App\DataFixtures;

use App\Entity\GroupeTag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class GroupeTagFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{


    public function load(ObjectManager $manager)
    {
        for ($g=0;$g<2;$g++){
            $grp= new GroupeTag();
          $grp->setLibelle("Groupe ".$g);
              if($g<1){
                  $grp->addTag($this->getReference("PHP"));
                  $grp  ->addTag($this->getReference("SQL"));
              }
              else{
                  $grp->addTag($this->getReference("SYMFONY"));
                  $grp  ->addTag($this->getReference("ANGULAR"));
              }

              $manager->persist($grp);

        }
        $manager->flush();

    }

    /**
     * @inheritDoc
     */
    public function getDependencies()
    {
        // TODO: Implement getDependencies() method.
        return array(
            TagFixtures::class
        );
    }

    /**
     * @inheritDoc
     */
    public static function getGroups(): array
    {
        // TODO: Implement getGroups() method.
        return ['groupeTag_Tag'];
    }
}
