<?php

class Utente {

    private $username;
    private $email;
    private $id;
    private $password;
    private $is_master = 0;
    private $attivo;
    private $lista_personaggi = array();

    /*
     * Preleva un utente dal DB usando il suo ID.
     */

    public function prelevaDaID($id, $conn) {
        if (!empty($this->email) || !empty($this->username))
            throw new Exception("Hai gia' caricato le informazioni nell'oggetto.");
        $query = $conn->prepare("SELECT * FROM `utente` WHERE `ID_Utente` = :id");
        $query->bindParam(":id", $id);
        $query->execute();
        $res = $query->fetchAll(PDO::FETCH_ASSOC);

        if (count($res) != 1)
            throw new Exception('Utente non esistente nel database');

        $riga = $res[0];
        $this->makeUser($riga);
    }

    /*
     * Preleva un utente dal DB usando le informazioni di login. Il primo parametro pu� essere l'username o l'email.
     */

    public function prelevaDaUsername($user_or_email, $conn) {
        $query = $conn->prepare("SELECT * FROM `utente` WHERE  `Email`  = :us_mail OR `Username`  = :us_mail");
        $query->bindParam(":us_mail", $user_or_email);
        $query->execute();
        $res = $query->fetchAll(PDO::FETCH_ASSOC);

        if (count($res) != 1)
            throw new Exception('Utente non esistente nel database');

        $riga = $res[0];
        $this->makeUser($riga);
    }

    public function prelevaPersonaggi($conn) {
        if ($this->id == NULL)
            throw new Exception("Manca l'ID dell'utente per il quale prelevare i personaggi");
        // Carichiamo in sessione i pg posseduti dall'utente
        $query = $conn->prepare("SELECT ID_Personaggio, Nome FROM `personaggio` WHERE `Proprietario`  = :id");
        $query->bindParam(":id", $this->id);
        $query->execute();
        $res = $query->fetchAll(PDO::FETCH_ASSOC);

        // Se l'utente � registrato ma non ha personaggi succede questo:
        if (count($res) == 0) {
            throw new Exception("L'utente non ha personaggi attivi: ");
        }
        // Se invece ha personagggi, carico in sessione coppie Nome/ID dei personaggi posseduti.
        // Servirà per capire a quali personaggi può accedere l'utente corrente.

        foreach ($res as $riga) {
            $nome = $riga['Nome'];
            $this->lista_personaggi[$nome] = $riga['ID_Personaggio'];
        }

        return $this->lista_personaggi;
    }

    public function memorizza($conn) {
        // Salva un nuovo utente nel db
        if (empty($this->username) || empty($this->email) || empty($this->password)) {
            throw new Exception("Mancano le informazioni per memorizzare un utente.");
        }

        $params = array(":user" => $this->username, ":email" => $this->email, ":pwd" => $this->password, ":isMaster" => $this->is_master, ":isActive" => $this->attivo);
        if ($this->id == NULL) {
            $query = $conn->prepare('INSERT INTO `utente`(`Username`, `Email`,`Pwd`,`Master`,`Attivo`) VALUES (:user, :email, :pwd, :isMaster, :isActive)');
            try {
                $query->execute($params);
            }
			catch (Exception $e) {
                if ($e->getCode() == '23000')
                    throw new Exception("Email o Username già utilizzato.");
            }
            $this->id = $conn->lastInsertId();
            return $this->id;
        } else {
            $query = $conn->prepare("UPDATE `utente` SET `Attivo` = :isActive, `Username` = :user, `Email` = :email, `Pwd` = :pwd, `Master` = :master WHERE `ID_Utente` = :id");
            $params = array(":id" => $this->id, ":user" => $this->username, ":email" => $this->email, ":pwd" => $this->password, ":master" => $this->is_master, ":isActive" => $this->attivo);
            $query->execute($params);
        }
    }

    public function cancella($conn) {
        if ($this->id == null)
            throw new Exception("Cancellazione fallita, l'id e' null");

        $query = $conn->prepare('DELETE FROM utente WHERE ID_Utente = :id');
        $query->bindParam(':id', $this->id);
        $query->execute();
        $righe = $query->rowCount();
        if ($righe < 1)
            throw new Exception("Cancellazione fallita");
    }

    public function getID() {
        return $this->id;
    }

    public function getUsername() {
        return $this->username;
    }

    public function is_Master() {
        return $this->is_master;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function isAttivo() {
        return $this->attivo;
    }

    public function stampaInfo() {
        echo $this->username . "<br />" . $this->password . "<br />" . $this->is_master . "<br />" . $this->id . "<br />" . $this->email;
    }

    public function impostaMaster($auth) {
        if (($auth == 0) || ($auth == 1)) {
            $this->is_master = $auth;
        }
        else
            throw new Exception("Valore non consentito : deve essere 0 o 1.");
    }

    public function impostaAttivo($auth) {
        if (($auth == 0) || ($auth == 1))
            $this->attivo = $auth;
        else
            throw new Exception("Valore non consentito : deve essere 0 o 1.");
    }

    public function impostaUsername($username) {
        $this->username = strip_tags(ucfirst(trim($username)));
    }

    public function impostaPassword($nuova_password) {
        require_once dirname(__FILE__) . "/../libs-backend/PHPAss/PasswordHash.php";
        $t_hasher = new PasswordHash(8, FALSE);
        $hash = $t_hasher->HashPassword(trim($nuova_password));
        $this->password = $hash;
    }

    public function impostaEmail($email) {
        /* Check email */
        $pattern = "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})^";
        if (!preg_match($pattern, $email)) {
            throw new Exception("Formato mail non valido");
        }
        $this->email = $email;
    }

    private function makeUser($riga) {
        $this->username = $riga['Username'];
        $this->email = $riga['Email'];
        $this->id = $riga['ID_Utente'];
        $this->password = $riga['Pwd'];
        $this->is_master = $riga['Master'];
        $this->attivo = $riga['Attivo'];
    }

}

?>