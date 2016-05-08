<?php

/**
 * Description of UserSession
 *
 * @author Demigod
 */
class UserSession {

    function __construct($ID_Utente, $Username, $Master, $sid, $timestamp, $addr) {
        $this->ID_Utente = $ID_Utente;
        $this->Username = $Username;
        $this->Master = $Master;
        $this->sid = $sid;
        $this->timestamp = $timestamp;
        $this->addr = $addr;
    }

    public function getID_Utente() {
        return $this->ID_Utente;
    }

    public function setID_Utente($ID_Utente) {
        $this->ID_Utente = $ID_Utente;
    }

    public function getUsername() {
        return $this->Username;
    }

    public function setUsername($Username) {
        $this->Username = $Username;
    }

    public function getMaster() {
        return $this->Master;
    }

    public function setMaster($Master) {
        $this->Master = $Master;
    }

    public function getSid() {
        return $this->sid;
    }

    public function setSid($sid) {
        $this->sid = $sid;
    }

    public function getTimestamp() {
        return $this->timestamp;
    }

    public function setTimestamp($timestamp) {
        $this->timestamp = $timestamp;
    }

    public function getAddr() {
        return $this->addr;
    }

    public function setAddr($addr) {
        $this->addr = $addr;
    }

    private $ID_Utente;
    private $Username;
    private $Master;
    private $sid;
    private $timestamp;
    private $addr;
}

?>
