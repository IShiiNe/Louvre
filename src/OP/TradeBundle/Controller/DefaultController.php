<?php

namespace OP\TradeBundle\Controller;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use OP\TradeBundle\Entity\Ticket;
use OP\TradeBundle\Form\TicketType;



class DefaultController extends Controller
{
    public function indexAction()
    {
        

    return $this->render('OPTradeBundle:Trade:layout.html.twig');
    }

    public function prepareAction()
    {
        $ticket = new Ticket();

        $form = $this->get('form.factory')->create(TicketType::class, $ticket);

        //if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
        //    $em = $this->getDoctrine()->getManager();
          //  $em->persist($ticket);
          //  $em->flush();

         //   $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');

         //   return $this->redirectToRoute('op_trade_homepage');
       // }

        return $this->render('OPTradeBundle:Trade:prepare.html.twig', array(
      'form' => $form->createView()
    ));
    }

    public function checkoutAction()
    {
        \Stripe\Stripe::setApiKey("sk_test_VISZ3fQWpHbM62RrrW2aUtQo");

        // Get the credit card details submitted by the form
        $token = $_POST['stripeToken'];

        // Create a charge: this will charge the user's card
        try {
            $charge = \Stripe\Charge::create(array(
                "amount" => 1000, // Amount in cents
                "currency" => "eur",
                "source" => $token,
                "description" => "Paiement Stripe - OpenClassrooms Exemple"
            ));
            $this->addFlash("success","Bravo ça marche !");
            return $this->redirectToRoute("op_trade_billeterie");
        } catch(\Stripe\Error\Card $e) {

            $this->addFlash("error","Snif ça marche pas :(");
            return $this->redirectToRoute("op_trade_billeterie");
            // The card has been declined
        }
    }
}
