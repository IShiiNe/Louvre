<?php
/**
 * Created by PhpStorm.
 * User: Raphaël
 * Date: 12/07/2017
 * Time: 15:01
 */

namespace Tests\OPTradeBundle\Service;

use OP\TradeBundle\Entity\Commande;
use OP\TradeBundle\Entity\Ticket;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use OP\TradeBundle\Calculator\OPCalculator;


class DefaultServiceTest extends WebTestCase
{
    public function testCalculator()
    {

        $calc = new OPCalculator();
        $commande = new Commande();
        $ticket1 = new Ticket();
        $ticket1->setDateBirth("1995-01-01");
        $ticket2 = new  Ticket();
        $ticket2->setDateBirth("1995-01-01");
        $ticket2->setReduced(1);
        $commande->addTicket($ticket1);
        $commande->addTicket($ticket2);
        $result = $calc->Calculate($commande);
        $this->assertEquals(26, $result['total']);


        $ticket1->setDateBirth("1995-01-01");
        $ticket2->setDateBirth("1955-01-01");
        $ticket2->setReduced(0);
        $result = $calc->Calculate($commande);
        $this->assertEquals(28, $result['total']);


        $ticket1->setDateBirth("1995-01-01");
        $ticket2->setDateBirth("2015-01-01");
        $result = $calc->Calculate($commande);
        $this->assertEquals(16, $result['total']);

    }

}