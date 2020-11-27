<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class TagFixtures extends Fixture implements FixtureGroupInterface
{
const tab=['PHP','SQL','SYMFONY','ANGULAR'];


    public function load(ObjectManager $manager)
    {
        for ($t=0;$t<4;$t++){
            $tag= new Tag();
            $tag->setLibelle(self::tab[$t])
                ->setDescriptif("description ".self::tab[$t]);
            $this->addReference(self::tab[$t],$tag);
            $manager->persist($tag);
        }
        $manager->flush();

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
