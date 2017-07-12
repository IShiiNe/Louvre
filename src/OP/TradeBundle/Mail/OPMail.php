<?php
/**
 * Created by PhpStorm.
 * User: Raphaël
 * Date: 07/07/2017
 * Time: 16:34
 */

namespace OP\TradeBundle\Mail;

use OP\TradeBundle\Entity\Commande;
use Symfony\Component\Templating\EngineInterface;



class OPMail
{
    protected $mailer;
    protected $templating;
    private $from = 'validation@louvre.com';
    private $reply = 'contact@louvre.com';
    private $name = 'Louvre Billetterie';

    public function __construct(\Swift_Mailer $mailer, EngineInterface $templating)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    public function sendMessage($to, $subject, $body)
    {
        $mail = \Swift_Message::newInstance();
        $mail
            ->setFrom(array($this->from => $this->name))
            ->setTo($to)
            ->setSubject($subject)
            ->setBody($body)
            ->setReplyTo(array($this->reply => $this->name))
            ->setContentType('text/html')
        ;
        $this->mailer->send($mail);
    }

    public function sendCommandeSuccess(Commande $commande, $prix)
    {
        $subject = "Louvre : Votre visite du ".$commande->getVisiteDate();
        $template = 'Emails/validation.html.twig';
        $to = $commande->getMail();
        $body = $this->templating->render($template, array('commande' => $commande, "prix" => $prix));
        $this->sendMessage($to, $subject, $body);
    }
}
