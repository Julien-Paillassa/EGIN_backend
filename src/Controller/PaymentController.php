<?php

namespace App\Controller;

use App\Entity\Board;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Id;
use Doctrine\Persistence\ObjectManager;
use Error;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Stripe;
use Stripe\Stripe as StripeStripe;
use Symfony\Component\Validator\Constraints\Json;
use Symfony\Component\HttpFoundation\JsonResponse;
use Psr\Log\LoggerInterface;

class PaymentController extends AbstractController
{

    public function __construct(EntityManagerInterface $em, LoggerInterface $logger)
    {
        $this->em = $em;
        $this->logger = $logger;
    }

    private $logger;
    private $em;


    /**
     * @Route("/payment", name="app_payment")
     */
    public function index(): Response
    {
        return $this->render('payment/index.html.twig', [
            'stripe_key' => $_ENV["STRIPE_KEY"],
        ]);
    }

    public function calculateOrderAmount(array $items): int
    {
        return 1400;
    }

    /**
     * @Route("api/create-checkout-session/{id}", name="app_payment_charge")
     */
    public function createCharge($id)
    {
        Stripe\Stripe::setApiKey($_ENV["STRIPE_SECRET"]);

        $board =  $this->em->getRepository(Board::class)->findOneBy(['id' => $id]);

        header('Content-Type: application/json');

        $YOUR_DOMAIN = 'http://localhost:4200';

        $checkout_session = \Stripe\Checkout\Session::create([
            "metadata" => ["board_id" => $board->getId()],
            'line_items' => [[
                // 'metadata' => [
                //     'boardId' => $board->getId(), //TODO: set this as the board ID
                // ],
                # Provide the exact Price ID (e.g. pr_1234) of the product you want to sell
                'price_data' => [
                    'currency' => 'usd',
                    'unit_amount' => $board->getPrice(), // TODO: set this as the board price
                    'product_data' => [
                        'name' => $board->getName(),
                        'description' => $board->getDescription(),
                        'images' => ['https://californiastreet.fr/15187-large_default/channel-islands-surfboards-sampler-futures-fins.jpg'],
                    ],
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/success?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
        ]);

        return new JsonResponse($checkout_session);
    }

    /**
     * @Route("api/webhooks", name="app_payment_webhooks")
     */
    public function WebHook(Request $request)
    {
        Stripe\Stripe::setApiKey($_ENV["STRIPE_SECRET"]);

        $endpoint_secret = 'whsec_c400668053295ecd639d14e67f98c80fa8b29838be89a12ccac54ac1687d2438';

        $payload = @file_get_contents('php://input');
        $event = null;

        try {
            $event = \Stripe\Event::constructFrom(
                json_decode($payload, true)
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            echo '⚠️  Webhook error while parsing basic request.';
            http_response_code(400);
            exit();
        }

        if ($endpoint_secret) {
            // Only verify the event if there is an endpoint secret defined
            // Otherwise use the basic decoded event
            $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
            try {
                $event = \Stripe\Webhook::constructEvent(
                    $payload,
                    $sig_header,
                    $endpoint_secret
                );
            } catch (\Stripe\Exception\SignatureVerificationException $e) {
                // Invalid signature
                echo '⚠️  Webhook error while validating signature.';
                http_response_code(400);
                exit();
            }
        }

        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $this->logger->info('TATA!');
                $this->logger->info($event->data->object->metadata->board_id);
                $this->logger->info('TOTO!');

                $board =  $this->em->getRepository(Board::class)->findOneBy(['id' => $event->data->object->metadata->board_id]);
                $board->setStatus('SOLD');
                $this->em->persist($board);
                $this->em->flush();

                //TODO: $event.data.object.metadata.boardId
                // TODO: Set the board status to sold
                //$paymentIntent = $event->data->object; // contains a \Stripe\PaymentIntent
                // Then define and call a method to handle the successful payment intent.
                // handlePaymentIntentSucceeded($paymentIntent);

                break;
            default:
                // Unexpected event type
                error_log('Received unknown event type');
        }

        return new Response(Response::HTTP_OK);
    }
}
