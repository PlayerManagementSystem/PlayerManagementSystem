<?php

class PG {

    private $nome;
    private $background;
    private $descrizione;
    private $proprietario;
    private $id;
    private $regno;
    private $razza;
    private $avatar;
    private $punti;
    private $punti_spesi;
    private $nota;
    private $lista_abilita = array();

    public function prelevaDaID($id, $conn) {
        if (!empty($this->nome) || !empty($this->background))
            throw new Exception("Hai gia' caricato le informazioni nell'oggetto.");

        $query = $conn->prepare('SELECT * FROM `personaggio` WHERE `ID_Personaggio` = :id');
        $query->bindParam(':id', $id);
        $query->execute();
        $res = $query->fetchAll(PDO::FETCH_ASSOC);
        if (count($res) == 0) {
            throw new Exception('PG\' non esistente nel database: ');
        }

        $riga = $res[0];

        $this->nome = $riga['Nome'];
        if ($riga['Background'] == NULL) {
            $riga['Background'] = "Nessun background inserito.";
        }
        $this->background = $riga['Background'];
        $this->razza = $riga['Razza'];
        $this->regno = $riga['Regno'];
        $this->id = $riga['ID_Personaggio'];
        if ($riga['Avatar'] == NULL)
            $riga['Avatar'] = "default.jpg";
        $this->avatar = $riga['Avatar'];
        $this->descrizione = $riga['Descrizione'];
        $this->proprietario = $riga['Proprietario'];
        $this->punti = $riga['Punti'];
        $this->nota = $riga['Nota'];
    }

    public function memorizza($conn) {
        // Salva una personaggio nel db
        if (empty($this->nome)) {
            throw new Exception("Manca il nome del personaggio");
        }
        if ($this->id == NULL) {
            $params = array(":nome" => $this->nome, ":bg" => $this->background, ":proprietario" => $this->proprietario, ":avt" => $this->avatar,":razza" => $this->razza, ":desc" => $this->descrizione, ":regno" => $this->regno, ":punti" => $this->punti, ":nota" => $this->nota);
            $query = $conn->prepare("INSERT INTO `personaggio`(`Nome`, `Background`,`Avatar`,`Razza`,`Descrizione`,`Regno`,`Proprietario`,`Punti`,`Nota`) VALUES (:nome, :bg, :avt,:razza, :desc, :regno, :proprietario,:punti,:nota)");
            $query->execute($params);
            $this->id = $conn->lastInsertId();
            return $this->id;
        } else {
            $params = array(":id" => $this->id, ":nome" => $this->nome, ":bg" => $this->background, ":avt" => $this->avatar,":razza" => $this->razza, ":proprietario" => $this->proprietario, ":desc" => $this->descrizione, ":regno" => $this->regno, ":punti" => $this->punti, ":nota" => $this->nota);
            $query = $conn->prepare("UPDATE  `personaggio` SET `Nome`= :nome, `Regno` = :regno, `Proprietario` = :proprietario, `Descrizione` = :desc, `Background` = :bg, `Avatar` = :avt, `Razza` = :razza, `Punti` = :punti, `Nota` = :nota WHERE `ID_Personaggio` = :id");
            $query->execute($params);
        }
    }

    /*     * Metodo statico per distruggere un personaggio */

    public function cancella($conn) {
        if ($this->id == null)
            throw new Exception("Cancellazione fallita, l'id e' null");

        $query = $conn->prepare("DELETE FROM personaggio WHERE ID_Personaggio = :id ");
        $query->bindParam(":id", $this->id);
        $query->execute();
        $righe = $query->rowCount();
        if ($righe < 1) {
            throw new Exception("Personaggio non trovato.");
        }
    }

    /*
     * Preleva e restituisce le abilità del personaggio specificato nel database. Oltre a restituire, conserva nella memoria dell'oggetto le abilità prelevate. Se questo metodo è invocato più volte sullo stesso oggetto, non accederà al database ma restituirà direttamente le abilità.
     */

    public function prelevaAbilita($conn) {
        if (!empty($this->lista_abilita)) {
            return $this->lista_abilita;
        }
        if ($this->id == NULL)
            throw new Exception("Questo personaggio non ha un id");

        $query = $conn->prepare("SELECT ID_Abilita,Nome,Descrizione,Costo,Note FROM abilita,competenze WHERE Personaggio = :id  AND Abilita = ID_Abilita");
        $query->bindParam(":id", $this->id);
        $query->execute();
        $res = $query->fetchAll(PDO::FETCH_ASSOC);

        if (count($res) == 0) {
            $this->punti_spesi = 0;
            throw new Exception('Abilita\' non trovate.');
        }
        $punti_spesi = 0;
        foreach ($res as $riga) {
            $punti_spesi += $riga['Costo'];
            array_push($this->lista_abilita, $riga);
        }
        $this->punti_spesi = $punti_spesi;
        return $this->lista_abilita;
    }

    public function impostaNome($nome) {
        $this->nome = strip_tags(ucwords(trim($nome)));
    }

    public function impostaNota($nota) {
        $this->nota = strip_tags(ucfirst(trim($nota)), "<b><p><br>");
    }

    public function impostaRegno($regno) {
        $this->regno = strip_tags(ucfirst(trim($regno)));
    }

    public function impostaBackground($background) {
        $this->background = strip_tags(ucfirst(trim($background)), "<b><p><br>");
    }

    public function impostaDescrizione($descrizione) {
        $this->descrizione = strip_tags(ucfirst(trim($descrizione)));
    }

    public function impostaRazza($razza) {
        $this->razza = strip_tags(ucfirst(trim($razza)));
    }

    public function impostaAvatar($avatar) {
        $this->avatar = $avatar;
    }

    public function impostaPunti($punti) {
        if (!is_numeric($punti))
            throw new Exception("I punti devono essere dei numeri.");
        $this->punti = $punti;
    }

    public function impostaProprietario($id_proprietario) {
        if (!isset($id_proprietario) || !is_numeric($id_proprietario)) {
            throw new Exception("L'id inviato non è corretto.");
        }
        $this->proprietario = $id_proprietario;
    }

    /*
     * Metodi di accesso
     */

    public function getNome() {
        return $this->nome;
    }

    public function getRegno() {
        if ($this->regno == NULL) {
            return "Nessuno";
        }
        return $this->regno;
    }

    public function getRazza() {
        return $this->razza;
    }

    public function getID() {
        return $this->id;
    }

    public function getDescrizione() {
        return $this->descrizione;
    }

    public function getBackground() {
        return $this->background;
    }

    public function getAvatar() {
        return $this->avatar;
    }

    public function getNota() {
        if ($this->nota == NULL)
            return "Nessuna nota";
        return $this->nota;
    }

    public function getPunti() {
        return $this->punti;
    }

    public function getPuntiSpesi() {
        return $this->punti_spesi;
    }

    public function stampaInfo() {
        echo $this->nome . "<br />" . $this->background . "<br />" . $this->avatar . "<br />" . $this->id . "<br />" . $this->proprietario;
    }

}

?>