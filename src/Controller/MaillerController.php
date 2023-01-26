<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use SendinBlue;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MaillerController extends AbstractController
{
    /**
     * @Route("/api/mailler", name="app_mailler")
     */
    public function sendEmail(MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->from('julien.paillassa@egmail.com')
            ->to('julien.paillassa@gmail.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        $mailer->send($email);

        return $this->render('mailler/index.html.twig', [
            'controller_name' => 'MaillerController',
        ]);
    }
}
