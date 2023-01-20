<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Board;

class BoardFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $board = new Board();
        $board->setName('La chocolatine');
        $board->setBrand('Nomads');
        $board->setDescription('La chocolatine a été nommée après un croissant de petit-déjeuner dans le sud-ouest de la France, et en particulier une référence au spot de surf de classe mondiale "La Piste". ');
        $board->setSize('5\'11');
        $board->setVolume('30L');
        $board->setDimension(' x 19\' 3/4" x 2\' 7/16" ');
        $board->setPrice(100);
        $board->setStatus('disponible');
        $manager->persist($board);
        $this->addReference('board_0', $board);

        $board = new Board();
        $board->setName('The don');
        $board->setBrand('Torq');
        $board->setDescription('Le Torq "The Don" est un Longboard très efficace pour ramer. Une planche parfaite pour surfer dans les petites et moyennes vagues. Lorsque le surf du pied arrière est très lâche, un nose large pour le noseriding, en bref il est polyvalent et efficace dans un large éventail de conditions.');
        $board->setSize('9\'1');
        $board->setVolume('62L');
        $board->setDimension(' x 19\' 3/4" x 2\' 7/16" ');
        $board->setPrice(200);
        $board->setStatus('disponible');
        $manager->persist($board);
        $this->addReference('board_1', $board);

        $board = new Board();
        $board->setName('La pod mod');
        $board->setBrand('Al Merrick');
        $board->setDescription('La Pod mod est une planche pour les surfeurs à la recherche d\'une board facile sans sacrifier la performance.');
        $board->setSize('5\'6');
        $board->setVolume('31L');
        $board->setDimension(' x 19\' 3/4" x 2\' 7/16" ');
        $board->setPrice(1000);
        $board->setStatus('disponible');
        $manager->persist($board);
        $this->addReference('board_2', $board);

        $board = new Board();
        $board->setName('La Kudat');
        $board->setBrand('Bradley');
        $board->setDescription('Ce modèle, du nom de la localité malaise de l\'île de Bornéo, la Kudat est avant tout très ludique ! Entre une planche minimalibu et une planche type Egg, avec un tail rond qui permet de manœuvrer plus facilement et avec légèreté, malgré le gros volume. La planche idéale pour surfer des petites et moyennes vagues et perfectionner son surf. Offre une bonne pagaie et une facilité d\'entrée dans les vagues.');
        $board->setSize('6\'4');
        $board->setVolume('37L');
        $board->setDimension(' x 19\' 3/4" x 2\' 7/16" ');
        $board->setPrice(2000);
        $board->setStatus('disponible');
        $manager->persist($board);
        $this->addReference('board_3', $board);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
        ];
    }
}
