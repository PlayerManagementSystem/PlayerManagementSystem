<?php

class Abilita {

    private $nome;
    private $descrizione;
    private $id;
    private $costo;
    private $note;

    /*
     *  Preleva il resto delle informazioni dal database usando l'ID
     *  Lancia eccezione se l'abilità non esiste o ha già gli altri campi fetchati.
     */

    public function prelevaDaID($id, $conn) {
        //Non è necessario fetchare se ho già caricato nell'oggetto le informazioni
        if (!empty($this->nome) || !empty($this->descrizione))
            throw new Exception("Hai gia' caricato le informazioni nell'oggetto.");
        $query = $conn->prepare("SELECT * FROM `abilita` WHERE `ID_Abilita` = :id");
        $query->bindParam(":id", $id);
        $query->execute();
        $res = $query->fetchAll(PDO::FETCH_ASSOC);

        if (count($res) == 0) {
            throw new Exception('Abilita\' non esistente nel database');
        }
        $riga = $res[0];
        $this->nome = $riga['Nome'];
        $this->descrizione = $riga['Descrizione'];
        $this->id = $riga['ID_Abilita'];
        $this->costo = $riga['Costo'];
        $this->note = $riga['Note'];
    }

    public function impostaNome($nome) {
        $this->nome = ucfirst(strip_tags(trim($nome)));
    }

    public function impostaDescrizione($descrizione) {
        $this->descrizione = ucfirst(strip_tags(trim($descrizione)));
    }

    public function impostaNote($note) {
        $this->note = ucfirst(strip_tags(trim($note)));
    }

    public function impostaCosto($costo) {
        if (!is_numeric($costo))
            throw new Exception("Il costo deve essere un numero.");
        $this->costo = $costo;
    }

    public function getCosto() {
        return $this->costo;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getNote() {
        return $this->note;
    }
    /*
     *  Preleva il resto delle informazioni dal database usando il nome dell'abilità
     *  Lancia eccezione se l'abilità non esiste o ha già gli altri campi fetchati.
     */

    public function prelevaDaNome($nome, $conn) {
        //Non è necessario fetchare se ho già caricato nell'oggetto le informazioni
        if (!empty($this->id) || !empty($this->descrizione))
            throw new Exception("Hai gia' caricato le informazioni nell'oggetto.");
        $query = $conn->prepare("SELECT * FROM `abilita` WHERE `Nome` = :nome");
        $query->bindParam(":nome", $nome);
        $query->execute();
        $res = $query->fetchAll(PDO::FETCH_ASSOC);
        if (count($res) == 0) {
            throw new Exception('Abilita\' non esistente nel database');
        }
        $riga = $res[0];
        $this->nome = $riga['Nome'];
        $this->descrizione = $riga['Descrizione'];
        $this->id = $riga['ID_Abilita'];
        $this->costo = $riga['Costo'];
        $this->note = $riga['Note'];
    }

    public function memorizza($conn) {
        //Salva una nuova abilità nel db
        if (empty($this->nome) || empty($this->descrizione)) {
            throw new Exception("Mancano delle info nell'abilità");
        }
        //Se l'abilità è nuova vuol dire che non ha un ID associato nel database
        if ($this->id == NULL) {
            $query = $conn->prepare("INSERT INTO `abilita`(`Nome`, `Descrizione`,`Costo`,`Note`) VALUES (:nome, :desc, :costo, :note)");
            try {
                $query->execute(array(":nome" => $this->nome, ":desc" => $this->descrizione, ":costo" => $this->costo,":note" => $this->note));
            } catch (Exception $e) {
                if ($e->getCode() == '23000')
                    throw new Exception("Nome abilità già utilizzato.");
            }

            $righe = $query->rowCount();
            //Lancia eccezione se l'inserimento fallisce poichè potrebbe essere già presente
            if ($righe < 1)
                throw new Exception("Inserimento abilita fallito.Esiste già per caso?");

            $this->id = $conn->lastInsertId();
        } else {
            $query = $conn->prepare("UPDATE `abilita` SET `Nome` = :nome, `Descrizione` = :desc,`Costo` = :costo,`Note` = :note WHERE ID_Abilita = :id");
            try {
                $query->execute(array(":nome" => $this->nome, ":desc" => $this->descrizione, ":id" => $this->id, ":costo" => $this->costo,":note" => $this->note));
            } catch (Exception $e) {
                if ($e->getCode() == '23000')
                    throw new Exception("Nome abilità già utilizzato.");
            }
            $righe = $query->rowCount();
            //Lancia eccezione se l'abilita' da modificare non esiste oppure non serve aggiornarla.
            if ($righe < 1)
                throw new Exception("Inserimento abilita fallito.Esiste già per caso?");
        }

        return $this->id;
    }

    /**
     * Cancella un'abilita dal database.
     * */
    public function delete($conn) {
        if ($this->id == null)
            throw new Exception("Cancellazione fallita, l'id e' null");
        $query = $conn->prepare("DELETE FROM abilita WHERE ID_Abilita = :id");
        $query->bindParam(":id", $this->id);
        $query->execute();
        $righe = $query->rowCount();
        if ($righe < 1)
            throw new Exception("Cancellazione fallita");
    }

    public function stampaInfo() {
        echo $this->nome . "<br />" . $this->descrizione . "<br />" . $this->id . "<br />" . $this->note;
    }

}

?>