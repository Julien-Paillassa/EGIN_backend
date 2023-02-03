<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class UserController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("api/userInfo/{email}", name="app_userInfo")
     */
    public function userInfo(string $email): Response
    {
        $user = $this->em->getRepository(User::class)->findBy(['email' => $email]);

        return $user;
    }
}
