<?php

namespace App\DataFixtures;

use App\Entity\SpotType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SpotTypeFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $spotType = new SpotType();
        $spotType->setName('Reef break');
        $manager->persist($spotType);
        $this->addReference('spotType_0', $spotType);

        $spotType = new SpotType();
        $spotType->setName('Beach break');
        $manager->persist($spotType);
        $this->addReference('spotType_1', $spotType);

        $spotType = new SpotType();
        $spotType->setName('Point break');
        $manager->persist($spotType);
        $this->addReference('spotType_2', $spotType);

        $manager->flush();
    }
}
