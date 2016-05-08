<?php

class Attivazione {

    private $email;
    private $id;
    private $codice;

    public function memorizza($conn) {
        if (empty($this->id) || empty($this->codice) || empty($this->email)) {
            throw new Exception("Sono necessarie ID,Codice,Email.");
        }

        $params = array(":id" => $this->id, ":mail" => $this->email, ":code" => $this->codice);
        $query = $conn->prepare("UPDATE `attivazioni` SET `ID_Da_Attivare` = :id, `Email` = :mail, `Codice` = :code WHERE `ID_Da_Attivare` = :id");
        $query->execute($params);
        $righe = $query->rowCount();
        if ($righe < 1) {
            $query = $conn->prepare("INSERT INTO `attivazioni`(`ID_Da_Attivare`, `Email`,`Codice`) VALUES (:id, :mail, :code)");
            $query->execute($params);
        }
    }

    public function prelevaDaID($id, $conn) {
        if (!isset($id) || !is_numeric($id)) {
            throw new Exception("Parametro ID non specificato o non corretto.");
        }
        if (!empty($this->email) || !empty($this->codice))
            throw new Exception("Hai gia' caricato le informazioni nell'oggetto.");
        $query = $conn->prepare("SELECT * FROM `attivazioni` WHERE `ID_Da_Attivare` = :id");
        $query->bindParam(":id", $id);
        $query->execute();
        $res = $query->fetchAll(PDO::FETCH_ASSOC);
        if (count($res) == 0) {
            throw new Exception('Nessun utente da attivare.');
        }
        $riga = $res[0];
        $this->email = $riga['Email'];
        $this->codice = $riga['Codice'];
        $this->id = $id;
    }

    public function distruggi($conn) {
        if (!isset($this->id) || empty($this->id)) {
            throw new Exception("L'oggetto non si riferisce ad alcun ID.");
        }
        $query = $conn->prepare("DELETE FROM `attivazioni` WHERE `ID_Da_Attivare` = :id");
        $query->bindParam(":id", $this->id);
        $query->execute();
        $righe = $query->rowCount();
        if ($righe < 1) {
            throw new Exception("Cancellazione non riuscita");
        }
    }

    public function getID() {
        return $this->id;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getCodice() {
        return $this->codice;
    }

    public function impostaID($id) {
        if (!isset($id) || !is_numeric($id)) {
            throw new Exception("L'ID deve essere numerico.");
        }
        $this->id = $id;
    }

    public function impostaEmail($email) {
        /* Check email */
        $pattern = "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})^";
        if (!preg_match($pattern, $email)) {
            throw new Exception("Email non valida.");
        }
        $this->email = $email;
    }

    public function impostaCodice($codice) {
        $this->codice = $codice;
    }

}

?>