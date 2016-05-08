<?php

class Messaggio {

    private $contenuto;

    /*
     * Restituisce il messaggio del master
     */

    public function getMessaggio($conn) {
        $res = $conn->query("SELECT messaggio FROM messaggio WHERE ID = 0");
        if ($res->rowCount() == 0)
            throw new Exception("Errore nel database.");
        $messaggio = $res->fetch(PDO::FETCH_ASSOC);
        $this->contenuto = $messaggio['messaggio'];
        return $messaggio['messaggio'];
    }

    public function memorizza($conn) {
        if (!isset($this->contenuto) || empty($this->contenuto)) {
            throw new Exception("Messaggio non settato");
        }
        $params = array(":mess" => $this->contenuto);
        $query = $conn->prepare("UPDATE `messaggio` SET `messaggio`= :mess WHERE `ID` = 0");
        $query->execute($params);
    }

    public function impostaMessaggio($messaggio) {
        $this->contenuto = $messaggio;
    }

}

?>