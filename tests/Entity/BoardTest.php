<?php

namespace App\Tests\Entity;

use App\Entity\Board;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BoardTest extends KernelTestCase
{

    public function getEntity(): Board
    {
        $board = new Board();
        $board->setName('Board test');
        $board->setBrand('Testor');
        $board->setDescription('Descritpion de test');
        $board->setSize('5\'11');
        $board->setVolume('40L');
        $board->setDimension(' x 19\' 3/4" x 2\' 7/16" ');
        $board->setPrice(1000);
        $board->setStatus('disponible');

        return $board;
    }

    public function asserHasErrors(Board $board, int $number = 0)
    {
        self::bootKernel();
        $container = static::getContainer();

        $errors = $container->get('validator')->validate($board);

        $messages = [];
        /** @var ConstraintViolation $error */
        foreach ($errors as $error) {
            $messages[] = $error->getPropertyPath() . ' => ' . $error->getMessage();
        }

        $this->assertCount($number, $errors, implode(',', $messages));
    }

    public function testValidBoard()
    {
        $board = $this->getEntity();

        $this->asserHasErrors($board, 0);
    }

    public function testInvalidBoard()
    {
        $board = $this->getEntity()->setPrice(-2000);

        $this->asserHasErrors($board, 1);
    }
}
