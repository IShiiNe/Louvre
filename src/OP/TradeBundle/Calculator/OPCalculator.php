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
        $dateActuel = $commande->getCommandeDate();
        $tickets = $commande->getTickets();


        foreach ($tickets as $ticket) {
            $numero ++;
            $birthdayDate = date("Y-m-d", strtotime($ticket->getDateBirth()));

            $dateNaissance = new \DateTime($birthdayDate);
            $interval = $dateNaissance->diff($dateActuel);
            $age = $interval->format('%Y');

            if (intval($age) >= 12 && intval($age) < 60 && !$ticket->getReduced() ) {
                $prix['ticket'.$numero] = 16;
                $total += 16;
            }elseif (intval($age) <= 4) {
                $prix['ticket'.$numero] = 0;
                $total += 0;
            }elseif ($ticket->getReduced()) {
                $prix['ticket'.$numero] = 10;
                $total += 10;
            }elseif (intval($age) >= 60) {
                $prix['ticket'.$numero] = 12;
                $total += 12;
            }else {
                $prix['ticket'.$numero] = 8;
                $total += 8;
            }
        }
        $prix['total'] = $total;


        $visiteDate = str_replace("/", "-", $commande->getVisiteDate());
        $visiteDate = date("Y-m-d", strtotime($visiteDate));
        $visiteDate = new \DateTime($visiteDate);
        $interval = $dateActuel->diff($visiteDate);

        if ($interval->format('%Y') == 0 && $interval->format('%m') == 0 && $interval->format('%d') == 0 && $interval->format('%R%h') <= -12) {
            $demiJournee = true;
            $commande->setDemiJournee($demiJournee);
        }

        return $prix;
    }
}