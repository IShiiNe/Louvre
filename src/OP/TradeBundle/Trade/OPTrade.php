<?php
/**
 * Created by PhpStorm.
 * User: Raphaël
 * Date: 07/07/2017
 * Time: 16:34
 */

namespace OP\TradeBundle\Trade;

use Doctrine\ORM\EntityManager;
use OP\TradeBundle\Entity\Dispo;
use OP\TradeBundle\Entity\Commande;



class OPTrade
{
    private $em = null;

    public function __construct($stripe_key, EntityManager $em) {
        $this->em = $em;
        \Stripe\Stripe::setApiKey($stripe_key);

    }


    public function payment(Commande $commande, $token, $total)
    {
        \Stripe\Charge::create(array(
            'amount'      => $total,
            'currency'    => 'eur',
            'source'      => $token,
            'description' => 'Paiement billet visite louvre',
        ));
        $this->sendOrder($commande);


    }

    private function sendOrder(Commande $commande) {
        $this->em->persist($commande);
        $this->em->flush();

        foreach ($commande->getTickets() as $ticket) {
            $ticket->setCommande($commande);
            $this->em->persist($ticket);
        }
        $this->em->flush();
    }

    /**
     * Met a jour la table dispo a jour de la commande passer
     *
     * @param object $commande
     *
     */
    public function dispo(Commande $commande) {

        $date = $commande->getVisiteDate();
        $numero = 0;
        $tickets = $commande->getTickets();
        foreach ($tickets as $ticket) {
            $numero += 1;
        }

        $dispo = $this->em->getRepository('OPTradeBundle:Dispo')->findWithDate($date);
        if (isset($dispo["0"])) {
            $numero += $dispo["0"]->getNombre();
            $dispo["0"]->setNombre($numero);
        }else {
            $dispo["0"] = new dispo();

            $dispo["0"]->setDate($date);
            $dispo["0"]->setNombre($numero);
        }
        $this->em->persist($dispo["0"]);
        $this->em->flush();
    }
}
