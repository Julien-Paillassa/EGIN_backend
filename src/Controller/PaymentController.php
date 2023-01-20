<?php

namespace App\Controller;

use App\Entity\Board;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Id;
use Error;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Stripe;
use Stripe\Stripe as StripeStripe;
use Symfony\Component\Validator\Constraints\Json;
use Symfony\Component\HttpFoundation\JsonResponse;

class PaymentController extends AbstractController
{

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

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
            'line_items' => [[
                # Provide the exact Price ID (e.g. pr_1234) of the product you want to sell
                'price_data' => [
                    'currency' => 'usd',
                    'unit_amount' => 20000,
                    'product_data' => [
                        'name' => $board->getName(),
                        'description' => $board->getDescription(),
                        'images' => ['https://californiastreet.fr/15187-large_default/channel-islands-surfboards-sampler-futures-fins.jpg'],
                    ],
                ],
                'quantity' => 1,
            ]],
            //'mode' => 'payment',
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/success?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
        ]);
        return new JsonResponse($checkout_session);
    }

    /**
     * @Route("api/webhooks", name="app_payment_webhooks")
     */
    // public function WebHook(Request $request)
    // {
    //     $endpoint_secret = 'pk_test_51MRtOJAGoQFo1sGmJTl83iKv6B9S24CxTYE64SzeoahKifATctHmaBifnn0V9d057uKsV1uPZ3wLSAiVkqGtoZSi00ivQA2BSV';

    //     $payload = @file_get_contents('php://input');
    //     $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
    //     $event = null;

    //     try {
    //         $event = \Stripe\Webhook::constructEvent(
    //             $payload,
    //             $endpoint_secret,
    //             $sig_header
    //         );

    //         //, $sig_header
    //     } catch (\UnexpectedValueException $e) {
    //         // Invalid payload
    //         return new Response(Response::HTTP_BAD_REQUEST);
    //         exit();
    //     } catch (\Stripe\Exception\SignatureVerificationException $e) {
    //         // Invalid signature
    //         return new Response(Response::HTTP_BAD_REQUEST);
    //         exit();
    //     }

    //     // Handle the event
    //     switch ($event->type) {
    //         case 'payment_intent.succeeded':
    //             $paymentIntent = $event->data->object; // contains a StripePaymentIntent
    //             handlePaymentIntentSucceeded($paymentIntent);
    //             break;
    //         case 'payment_method.attached':
    //             $paymentMethod = $event->data->object; // contains a StripePaymentMethod
    //             handlePaymentMethodAttached($paymentMethod);
    //             break;
    //             // ... handle other event types
    //         default:
    //             // Unexpected event type

    //             return new Response(Response::HTTP_BAD_REQUEST);
    //             exit();
    //     }

    //     return new Response(Response::HTTP_OK);
    // }
}
