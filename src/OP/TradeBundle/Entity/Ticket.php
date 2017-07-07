<?php

namespace OP\TradeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use OP\TradeBundle\Entity\Commande;

/**
 * Ticket
 *
 * @ORM\Table(name="ticket")
 * @ORM\Entity(repositoryClass="OP\TradeBundle\Repository\TicketRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Ticket
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="Name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="date_birth", type="string", length=255)
     */
    private $dateBirth;

    /**
     * @var bool
     *
     * @ORM\Column(name="reduced", type="boolean")
     */
    private $reduced;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255)
     */
    private $country;

    /**
     * @ORM\ManyToOne(targetEntity="OP\TradeBundle\Entity\Commande", inversedBy="tickets")
     * @ORM\JoinColumn()
     */
    private $commande;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Ticket
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set dateBirth
     *
     * @param string $dateBirth
     *
     * @return Ticket
     */
    public function setDateBirth($dateBirth)
    {
        $this->dateBirth = $dateBirth;

        return $this;
    }

    /**
     * Get dateBirth
     *
     * @return string
     */
    public function getDateBirth()
    {
        return $this->dateBirth;
    }

    /**
     * Set reduced
     *
     * @param boolean $reduced
     *
     * @return Ticket
     */
    public function setReduced($reduced)
    {
        $this->reduced = $reduced;

        return $this;
    }

    /**
     * Get reduced
     *
     * @return bool
     */
    public function getReduced()
    {
        return $this->reduced;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return Ticket
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set commande
     *
     * @param \OP\TradeBundle\Entity\Commande $commande
     *
     * @return Ticket
     */
    public function setCommande(\OP\TradeBundle\Entity\Commande $commande)
    {
        $this->commande = $commande;


        return $this;
    }

    /**
     * Get commande
     *
     * @return \OP\TradeBundle\Entity\Commande
     */
    public function getCommande()
    {
        return $this->commande;
    }



}

