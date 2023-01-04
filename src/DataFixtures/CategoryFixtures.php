<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Category;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $category = new Category();
        $category->setName('ShortBoard');
        $manager->persist($category);
        $this->addReference('category_0', $category);

        $category = new Category();
        $category->setName('Longboard');
        $manager->persist($category);
        $this->addReference('category_1', $category);

        $category = new Category();
        $category->setName('Fish');
        $manager->persist($category);
        $this->addReference('category_2', $category);

        $category = new Category();
        $category->setName('Evolutive');
        $manager->persist($category);
        $this->addReference('category_3', $category);

        $category = new Category();
        $category->setName('Mini-Malibu');
        $manager->persist($category);
        $this->addReference('category_4', $category);

        $category = new Category();
        $category->setName('Gun');
        $manager->persist($category);
        $this->addReference('category_5', $category);

        $manager->flush();
    }
}
