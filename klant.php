<?php

class Klant
{
    private $id;
    private $naam;
    private $email;

// Constructor
    public function __construct($id, $naam, $email)
    {
        $this->id = $id;
        $this->naam = $naam;
        $this->email = $email;
    }

// Getter en setter voor ID
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

// Getter en setter voor naam
    public function getNaam()
    {
        return $this->naam;
    }

    public function setNaam($naam)
    {
        $this->naam = $naam;
    }

// Getter en setter voor email
    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }
}