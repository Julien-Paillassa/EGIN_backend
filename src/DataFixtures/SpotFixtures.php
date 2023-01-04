<?php

namespace App\DataFixtures;

use App\Entity\Spot;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SpotFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $spot = new Spot();
        $spot->setName('Plage Nord');
        $spot->setCodePostal('33000');
        $spot->setCoordinate('X: 44.866669  Y: -1.091');
        $spot->setdescription('
        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam tincidunt porta porttitor. Sed quis tempor ante. Aliquam nec dapibus sem, vehicula scelerisque leo. Integer ac justo quam. Praesent suscipit tincidunt malesuada. Suspendisse nec sem nec ipsum porttitor lobortis at sit amet nisl. Nam urna lectus, ultricies sit amet massa eget.');
        $manager->persist($spot);
        $this->addReference('spot_0', $spot);

        $spot = new Spot();
        $spot->setName('Plage centrale');
        $spot->setCodePostal('45000');
        $spot->setCoordinate('X: 44.981998  Y: -1.0804');
        $spot->setdescription('
        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam tincidunt porta porttitor. Sed quis tempor ante. Aliquam nec dapibus sem, vehicula scelerisque leo. Integer ac justo quam. Praesent suscipit tincidunt malesuada. Suspendisse nec sem nec ipsum porttitor lobortis at sit amet nisl. Nam urna lectus, ultricies sit amet massa eget.');
        $manager->persist($spot);
        $this->addReference('spot_1', $spot);

        $spot = new Spot();
        $spot->setName('Les culs nus');
        $spot->setCodePostal('64000');
        $spot->setCoordinate('X: 43.666672  Y: -1.45');
        $spot->setdescription('
        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam tincidunt porta porttitor. Sed quis tempor ante. Aliquam nec dapibus sem, vehicula scelerisque leo. Integer ac justo quam. Praesent suscipit tincidunt malesuada. Suspendisse nec sem nec ipsum porttitor lobortis at sit amet nisl. Nam urna lectus, ultricies sit amet massa eget.');
        $manager->persist($spot);
        $this->addReference('spot_2', $spot);

        $spot = new Spot();
        $spot->setName('Le VVF');
        $spot->setCodePostal('31000');
        $spot->setCoordinate('X: 43.48333  Y: -1.53333');
        $spot->setdescription('
        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam tincidunt porta porttitor. Sed quis tempor ante. Aliquam nec dapibus sem, vehicula scelerisque leo. Integer ac justo quam. Praesent suscipit tincidunt malesuada. Suspendisse nec sem nec ipsum porttitor lobortis at sit amet nisl. Nam urna lectus, ultricies sit amet massa eget.');
        $manager->persist($spot);
        $this->addReference('spot_3', $spot);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            SpotTypeFixtures::class,
        ];
    }
}
