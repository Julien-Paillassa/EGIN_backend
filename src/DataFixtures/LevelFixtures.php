<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Level ;

class LevelFixtures extends Fixture 
{
    public function load(ObjectManager $manager)
    {
        $level  = new Level ();
        $level ->setName('débutant');
        $manager->persist($level );
        $this->addReference('level _0', $level );

        $level  = new Level ();
        $level ->setName('intermédiare');
        $manager->persist($level );
        $this->addReference('level _1', $level );

        $level  = new Level ();
        $level ->setName('confirmé');
        $manager->persist($level );
        $this->addReference('level _2', $level );

        $level  = new Level ();
        $level ->setName('professionnel');
        $manager->persist($level );
        $this->addReference('level _3', $level );

        $manager->flush();
    }
}
