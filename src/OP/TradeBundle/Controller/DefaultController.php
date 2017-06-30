<?php

namespace OP\TradeBundle\Controller;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use OP\TradeBundle\Entity\Commande;
use OP\TradeBundle\Form\CommandeType;



class DefaultController extends Controller
{
    public function indexAction()
    {
        

    return $this->render('OPTradeBundle:Trade:layout.html.twig');
    }

    public function prepareAction(Request $request)
    {
        $commande = new Commande();

        $form = $this->get('form.factory')->create(CommandeType::class, $commande);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            $calculator = $this->container->get('op_trade.calculator');
            $prix = $calculator->calculate($commande);
            $_SESSION['commande'] = $commande;

            return $this->redirectToRoute('op_trade_checkout');
        }

        return $this->render('OPTradeBundle:Trade:prepare.html.twig', array(
      'form' => $form->createView()
    ));
    }

    public function checkoutAction()
    {
        var_dump($_SESSION);
        die;
        return $this->render('OPTradeBundle:Trade:checkout.html.twig');
    }

    public function payAction()
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
            return $this->redirectToRoute("op_trade_finish");
        } catch(\Stripe\Error\Card $e) {

            $this->addFlash("error","Snif ça marche pas :(");
            return $this->redirectToRoute("op_trade_finish");
            // The card has been declined
        }
    }

    public function finishAction()
    {
        return $this->render('OPTradeBundle:Trade:finish.html.twig');
    }
}
