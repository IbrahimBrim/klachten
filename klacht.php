<?php
class Klacht {
    private $id;
    private $klantenId;
    private $omschrijving;
    private $statuss;
    private $foto;
    private $lon;
    private $lan;

    // Constructor
    public function __construct($id, $klantenId, $omschrijving,$statuss, $foto, $lon, $lan) {
        $this->id = $id;
        $this->klantenId = $klantenId;
        $this->omschrijving = $omschrijving;
        $this->statuss= $statuss;
        $this->foto = $foto;
        $this->lon = $lon;
        $this->lan = $lan;
    }

    // Getter en setter voor ID
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    // Getter en setter voor klantenId
    public function getKlantenId() {
        return $this->klantenId;
    }

    public function setKlantenId($klantenId) {
        $this->klantenId = $klantenId;
    }

    // Getter en setter voor omschrijving
    public function getOmschrijving() {
        return $this->omschrijving;
    }

    public function setStatuss($statuss) {
        $this->statuss = $statuss;
    }
    // Getter en setter voor omschrijving
    public function getstatuss() {
        return $this->statuss;
    }

    public function setOmschrijving($omschrijving) {
        $this->omschrijving = $omschrijving;
    }
    // Getter en setter voor foto
    public function getFoto() {
        return $this->foto;
    }

    public function setFoto($foto) {
        $this->foto = $foto;
    }

    // Getter en setter voor lon
    public function getLon() {
        return $this->lon;
    }

    public function setLon($lon) {
        $this->lon = $lon;
    }

    // Getter en setter voor lan
    public function getLan() {
        return $this->lan;
    }

    public function setLan($lan) {
        $this->lan = $lan;
    }
}


