<?php

class Competenza {

    private $pg;
    private $abilita;
    private $grado;

    //Da aggiungere nella nuova versione.Modifica delle competenze.
    //private $is_new = 1;
    public function impostaPG($personaggio) {
        if (!is_numeric($personaggio))
            throw new Exception("Deve essere un numero.");
        $this->pg = $personaggio;
    }

    public function impostaAbilita($abilita) {
        if (!is_numeric($abilita))
            throw new Exception("Deve essere un numero.");
        $this->abilita = $abilita;
    }

    /*
     * Per ora questa funzione non serve. Le competenze non si editano. Vengono cancellate direttamente.
     *
      public function prelevaEsistente($abilita,$personaggio, $conn) {
      //Non è necessario fetchare se ho già caricato nell'oggetto le informazioni
      if (!empty($this -> pg) || !empty($this -> abilita))
      throw new Exception("Hai gia' caricato le informazioni nell'oggetto.");
      $query = $conn -> prepare("SELECT * FROM `competenze` WHERE `Abilita` = :abilita AND `Personaggio` = :pg");
      $query -> execute(array(":abilita" => $this -> abilita, ":pg" => $this -> pg));
      $res = $query -> fetchAll(PDO::FETCH_ASSOC);
      if (count($res) == 0) {
      throw new Exception('Competenza non esistente nel database');
      }
      $riga = $res[0];
      $this -> pg = $riga['Personaggio'];
      $this -> abilita = $riga['Abilita'];
      $this -> grado = $riga['Grado'];
      $this -> is_new = 0;
      }
     */

    public function memorizza($conn) {
        //Salva una nuova competenza nel db
        if (empty($this->pg) || empty($this->abilita)) {
            throw new Exception("Mancano delle info nell'abilità");
        }
        //Se l'abilità è nuova vuol dire che non ha un ID associato nel database
        //if ($this -> is_new == 1) {
        $query = $conn->prepare("INSERT INTO `competenze`(`Abilita`, `Personaggio`) VALUES (:ab, :pg)");
        try {
            $query->execute(array(":ab" => $this->abilita, ":pg" => $this->pg));
        } catch (Exception $e) {
            if ($e->getCode() == '23000')
                throw new Exception("Competenza già assegnata.");
        }
        $righe = $query->rowCount();
        //Lancia eccezione se l'inserimento fallisce poichè potrebbe essere già presente
        if ($righe < 1)
            throw new Exception("Inserimento abilita fallito.Esiste già per caso?");
        /* } else {
          $query = $conn -> prepare("UPDATE `abilita` SET `Nome` = :nome, `Descrizione` = :desc WHERE ID_Abilita = :id");
          $query -> execute(array(":nome" => $this -> nome, ":desc" => $this -> descrizione, ":id" => $this -> id));
          $righe = $query -> rowCount();
          //Lancia eccezione se l'abilita' da modificare non esiste oppure non serve aggiornarla.
          if ($righe < 1)
          throw new Exception("Inserimento abilita fallito.Esiste già per caso?");
          } */
    }

    /**
     * Cancella una competenza dal database.
     * */
    public function delete($conn) {
        if ($this->pg == null || $this->abilita == null)
            throw new Exception("Devi specificare l'ID del pg e dell'abilità da cancellare.");
        $query = $conn->prepare("DELETE FROM competenze WHERE Abilita = :ab AND Personaggio = :pg");
        $query->execute(array(":ab" => $this->abilita, ":pg" => $this->pg));
        $righe = $query->rowCount();
        if ($righe < 1)
            throw new Exception("Cancellazione fallita");
    }

}

?>