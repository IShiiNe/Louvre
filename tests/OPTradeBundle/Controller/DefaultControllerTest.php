<?php

namespace Tests\OPTradeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


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

}
