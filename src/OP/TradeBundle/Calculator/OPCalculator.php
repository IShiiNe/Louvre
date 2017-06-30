<?php
/**
 * Created by PhpStorm.
 * User: Raphaël
 * Date: 30/06/2017
 * Time: 16:25
 */

namespace OP\TradeBundle\Calculator;

use OP\TradeBundle\Entity\Commande;


class OPCalculator
{
    /**
     * Détermine le prix de chaque ticket et donne son total
     *
     * @param object $commande
     * @return array
     */
    public function Calculate(Commande $commande)
    {
        $total = 0;
        $numero = 0;
        $dateActuel = new \DateTime();
        $tickets = $commande->getTickets();
        foreach ($tickets as $ticket) {
            $numero ++;
            $birthdayDate = date("Y-m-d", strtotime($ticket->getDateBirth()));

            $dateNaissance = new \DateTime($birthdayDate);
            $interval = $dateNaissance->diff($dateActuel);
            $age = $interval->format('%Y');

            if (intval($age) >= '16' && intval($age) < '60' && !$ticket->getReduced() ) {
                $prix[] = array('ticket '.$numero => '16');
                $total += 16;
            }elseif (intval($age) <= '4') {
                $prix[] = array('ticket '.$numero => '0');
                $total += 0;
            }elseif ($ticket->getReduced()) {
                $prix[] = array('ticket '.$numero => '10');
                $total += 0;
            }else {
                $prix[] = array('ticket '.$numero => '12');
                $total += 12;
            }
        }
        $prix[] = array('total' => $total);
        return $prix;
    }
}