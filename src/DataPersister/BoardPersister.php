<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\Board;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Twig\Environment;

class BoardPersister implements DataPersisterInterface
{

    public function __construct(LoggerInterface $logger, Environment $twig, EntityManagerInterface $em)
    {
        $this->logger = $logger;
        $this->twig = $twig;
        $this->em = $em;
    }

    public function supports($data): bool
    {
        return $data instanceof Board;
    }

    public function persist($data)
    {
        $this->em->persist($data);
        $this->em->flush();

        //$this->em->getRepository(FichierAppellation::class)->deleteAlone();

        return $data;
    }

    public function remove($data)
    {
        throw new \Exception('not supported!');
    }
}
