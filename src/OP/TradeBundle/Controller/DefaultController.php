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
        if (isset($_SESSION['commande'])) {
            $commande = $_SESSION['commande'];

            return $this->render('OPTradeBundle:Trade:layout.html.twig', array(
                'commande' => $commande,
            ));
        }else {
            return $this->render('OPTradeBundle:Trade:layout.html.twig');
        }


    }

    public function prepareAction(Request $request)
    {
        $commande = new Commande();

        $form = $this->get('form.factory')->create(CommandeType::class, $commande);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $_SESSION['commande'] = $commande;

            return $this->redirectToRoute('op_trade_checkout');
        }

        if (isset($_SESSION['commande'])) {
            $commande = $_SESSION['commande'];

            return $this->render('OPTradeBundle:Trade:prepare.html.twig', array(
                'form' => $form->createView(),
                'commande' => $commande,
            ));
        }
        return $this->render('OPTradeBundle:Trade:prepare.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function checkoutAction()
    {
        if (!isset($_SESSION['commande'])) {
            throw $this->createNotFoundException("Le panier est vide.");
        }
        $calculator = $this->container->get('op_trade.calculator');
        $prix = $calculator->calculate($_SESSION['commande']);
        $commande = $_SESSION['commande'];

        return $this->render('OPTradeBundle:Trade:checkout.html.twig', array(
            'commande' => $commande,
            'prix' => $prix,
        ));
    }

    public function payAction()
    {

        \Stripe\Stripe::setApiKey("sk_test_VISZ3fQWpHbM62RrrW2aUtQo");

        if (!isset($_POST['stripeToken'])) {
            throw $this->createNotFoundException("Vous ne devrier pas vous trouver ici");
        }
        // Get the credit card details submitted by the form
        $token = $_POST['stripeToken'];

        $calculator = $this->container->get('op_trade.calculator');
        $prix = $calculator->calculate($_SESSION['commande']);
        $total = $prix['total'] * 100 ;
        // Create a charge: this will charge the user's card
        try {
            $charge = \Stripe\Charge::create(array(
                "amount" => $total, // Amount in cents
                "currency" => "eur",
                "source" => $token,
                "description" => "Paiement billet visite louvre"
            ));


            $commande = $_SESSION['commande'];
            $mail = $_POST['stripeEmail'];
            $commande->setPrix($prix['total']);
            $commande->setMail($mail);
            $em = $this->getDoctrine()->getManager();
            $em->persist($commande);
            $em->flush();


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
        unset($_SESSION["commande"]);
        return $this->render('OPTradeBundle:Trade:finish.html.twig');
    }
}
