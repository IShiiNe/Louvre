<?php

namespace OP\TradeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('OPTradeBundle:Trade:layout.html.twig');
    }
}
