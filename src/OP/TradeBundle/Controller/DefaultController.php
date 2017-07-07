<?php

namespace OP\TradeBundle\Controller;


use OP\TradeBundle\Entity\Ticket;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use OP\TradeBundle\Entity\Commande;
use OP\TradeBundle\Form\CommandeType;



class DefaultController extends Controller
{
    public function indexAction()
    {
        $session = $this->get('session');

        if ($session->has('commande')) {
            $commande = $session->get('commande');

            return $this->render('OPTradeBundle:Trade:layout.html.twig', array(
                'commande' => $commande,
            ));
        }else {
            return $this->render('OPTradeBundle:Trade:layout.html.twig');
        }


    }

    public function prepareAction(Request $request)
    {
        $session = $this->get('session');

        $commande = new Commande();
        $dispo = $this->getDoctrine()->getManager()->getRepository('OPTradeBundle:Dispo')->findAll();


        $form = $this->get('form.factory')->create(CommandeType::class, $commande);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $session->set('commande', $commande);
            return $this->redirectToRoute('op_trade_checkout');
        }
        if ($session->has('commande')) {
            $panier = $session->get('commande');

            return $this->render('OPTradeBundle:Trade:prepare.html.twig', array(
                'form' => $form->createView(),
                'commande' => $panier,
                'dispos' => $dispo,
            ));
        }
        return $this->render('OPTradeBundle:Trade:prepare.html.twig', array(
            'form' => $form->createView(),
            'dispos' => $dispo,
        ));
    }

    public function checkoutAction()
    {
        $session = $this->get('session');
        if (!$session->has('commande')) {
            throw $this->createNotFoundException("Le panier est vide.");
        }
        $calculator = $this->container->get('op_trade.calculator');
        $commande = $session->get('commande');
        $prix = $calculator->calculate($commande);

        return $this->render('OPTradeBundle:Trade:checkout.html.twig', array(
            'commande' => $commande,
            'prix' => $prix,
        ));
    }

    public function payAction()
    {
        if (!isset($_POST['stripeToken'])) {
            throw $this->createNotFoundException("Vous ne devrier pas vous trouver ici");
        }
        // Get the credit card details submitted by the form
        $token = $_POST['stripeToken'];
        $session = $this->get('session');
        $commande = $session->get('commande');

        $calculator = $this->container->get('op_trade.calculator');
        $prix = $calculator->calculate($commande);
        $total = $prix['total'] * 100 ;
        $commande->setPrix($prix['total']);
        $commande->setMail($_POST['stripeEmail']);

        $trade =  $this->container->get('op_trade.trade');


        // Create a charge: this will charge the user's card
        try {

            $trade->Payment($commande, $token, $total);

            $trade->dispo($commande);

            $mail =  $this->container->get('op_trade.mail');
            $mail->sendCommandeSuccess($commande);


            $session->set('finish', "ok");
            $this->addFlash("success","Paiement accepté !");
            return $this->redirectToRoute("op_trade_finish");
        } catch(\Stripe\Error\Card $e) {

            $this->addFlash("error","Un probleme est survenu");
            return $this->redirectToRoute("op_trade_finish");
            // The card has been declined
        }
    }

    public function finishAction()
    {
        $session = $this->get('session');
        if ($session->has('finish')) {
            $session->clear();
            return $this->render('OPTradeBundle:Trade:finish.html.twig', array('valider' => "ok"));
        }elseif ($session->has('commande')) {
            $session->clear();
            return $this->render('OPTradeBundle:Trade:finish.html.twig', array('vider' => "ok"));
        }else {
            throw $this->createNotFoundException("Vous ne devrier pas vous trouver ici");
        }
    }
}
