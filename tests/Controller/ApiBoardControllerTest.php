<?php

namespace App\Test\Controller;

use App\Entity\Board;
use App\Repository\BoardRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiBoardControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private BoardRepository $repository;
    private string $path = '/api/board/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = (static::getContainer()->get('doctrine'))->getRepository(Board::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Board index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'board[brand]' => 'Testing',
            'board[name]' => 'Testing',
            'board[size]' => 'Testing',
            'board[volume]' => 'Testing',
            'board[description]' => 'Testing',
            'board[dimension]' => 'Testing',
        ]);

        self::assertResponseRedirects('/api/board/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Board();
        $fixture->setBrand('My Title');
        $fixture->setName('My Title');
        $fixture->setSize('My Title');
        $fixture->setVolume('My Title');
        $fixture->setDescription('My Title');
        $fixture->setDimension('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Board');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Board();
        $fixture->setBrand('My Title');
        $fixture->setName('My Title');
        $fixture->setSize('My Title');
        $fixture->setVolume('My Title');
        $fixture->setDescription('My Title');
        $fixture->setDimension('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'board[brand]' => 'Something New',
            'board[name]' => 'Something New',
            'board[size]' => 'Something New',
            'board[volume]' => 'Something New',
            'board[description]' => 'Something New',
            'board[dimension]' => 'Something New',
        ]);

        self::assertResponseRedirects('/api/board/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getBrand());
        self::assertSame('Something New', $fixture[0]->getName());
        self::assertSame('Something New', $fixture[0]->getSize());
        self::assertSame('Something New', $fixture[0]->getVolume());
        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getDimension());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Board();
        $fixture->setBrand('My Title');
        $fixture->setName('My Title');
        $fixture->setSize('My Title');
        $fixture->setVolume('My Title');
        $fixture->setDescription('My Title');
        $fixture->setDimension('My Title');

        $this->repository->add($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/api/board/');
    }
}
