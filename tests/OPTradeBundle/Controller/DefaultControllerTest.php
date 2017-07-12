<?php

namespace Tests\OPTradeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use OP\TradeBundle\Entity\Commande;
use OP\TradeBundle\Entity\Ticket;


class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $client->request('GET', "/");

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testPrepare()
    {
        $client = static::createClient();

        $client->request('GET', "/order/prepare");

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }







    /**
     * Provides all valid application URLs.
     *
     * @return array The list of all valid application URLs.
     */
    public function provideUrls()
    {
        return array(
            array('/'),
            array('/order/prepare'),
            array('/order/checkout'),
            array('/order/pay'),
            array('/order/finish'),
            );
    }
}
