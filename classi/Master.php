<?php

class Master {
    /*
     * Restituisce le email dei master.
     */

    public static function getEmailMasters($conn) {
        $res = $conn->query("SELECT Email FROM `utente` WHERE `Master` = 1");
        if ($res->rowCount() == 0) {
            throw new Exception("Master non esistenti...");
        }
        $emails = array();
        foreach ($res as $riga) {
            array_push($emails, $riga['Email']);
        }
        return $emails;
    }

    /*
     * Restituisce un array contenente informazioni semplici sui personaggi in gioco.
     */

    public static function getPersonaggi($conn) {
        $res = $conn->query("SELECT Username,Nome,Regno,ID_Personaggio,Avatar FROM `personaggio`,`utente` WHERE ID_Utente = Proprietario");
        if ($res->rowCount() == 0) {
            throw new Exception('Non ci sono personaggi.');
        }
        $personaggi = array();
        foreach ($res as $riga) {
            $personaggio['Nome'] = $riga['Nome'];
            if ($riga['Regno'] == NULL) {
                $personaggio['Regno'] = "Nessuno";
            }
            else
                $personaggio['Regno'] = $riga['Regno'];
            $personaggio['ID'] = $riga['ID_Personaggio'];
            if ($riga['Avatar'] == NULL)
                $personaggio['Avatar'] = "default.jpg";
            else
                $personaggio['Avatar'] = $riga['Avatar'];
            $personaggio['Proprietario'] = $riga['Username'];
            array_push($personaggi, $personaggio);
        }
        return $personaggi;
    }

    /*
     * Conteggia i costi per ciascun personaggio presente
     *
     */

    public static function conteggiaCosti($conn) {
        $res = $conn->query("SELECT sum(Costo) as Somma,personaggio.Nome,Punti FROM personaggio,competenze,abilita WHERE ID_Personaggio = Personaggio AND Abilita = ID_Abilita GROUP BY personaggio.Nome");
        if ($res->rowCount() == 0) {
            throw new Exception('Errore');
        }
        $lista = array();
        foreach ($res as $riga) {
            $elemento['somma_spesa'] = $riga['Somma'];
            $elemento['nome'] = $riga['Nome'];
            $elemento['punti'] = $riga['Punti'];
            array_push($lista, $elemento);
        }
        return $lista;
    }

    /*
     * Restituisce un array contentente le abilità in gioco.
     */

    public static function getAbilita($conn) {
        $arr_abilita = array();
        $res = $conn->query("SELECT * FROM abilita");
        if ($res->rowCount() == 0)
            throw new Exception("Non ci sono abilità");
        foreach ($res as $riga) {
            $abilita['id'] = $riga['ID_Abilita'];
            $abilita['descrizione'] = $riga['Descrizione'];
            $abilita['nome'] = $riga['Nome'];
            $abilita['costo'] = $riga['Costo'];
            $abilita['note'] = $riga['Note'];
            array_push($arr_abilita, $abilita);
        }

        return $arr_abilita;
    }

    /*
     * Restituisce un array contenente ID,Username per ogni giocatore.
     */

    public static function getUtenti($conn) {
        $arr_utenti = array();
        $res = $conn->query("SELECT ID_Utente,Username,Email,Master FROM utente");
        if ($res->rowCount() == 0)
            throw new Exception("Non ci sono utenti");
        foreach ($res as $riga) {
            $utente['id'] = $riga['ID_Utente'];
            $utente['username'] = $riga['Username'];
            $utente['email'] = $riga['Email'];
            if ($riga['Master'] == 1)
                $utente['master'] = "Si";
            else
                $utente['master'] = "No";

            array_push($arr_utenti, $utente);
        }

        return $arr_utenti;
    }

}

?>