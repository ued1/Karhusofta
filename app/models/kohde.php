<?php

class Kohde extends BaseModel {

    public $kohdeid, $nimi, $osoite, $kuvaus, $arvo;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validoi_nimi', 'validoi_arvo', 'validoi_osoite', 'validoi_kuvaus');
    }

    public static function kaikki() {
        $kysely = DB::connection()->prepare('SELECT * FROM Kohde');
        $kysely->execute();
        $rivit = $kysely->fetchAll();
        $kohteet = array();

        foreach ($rivit as $rivi) {
            $kohteet[] = new Kohde(array(
                'kohdeid' => $rivi['kohdeid'],
                'nimi' => $rivi['nimi'],
                'osoite' => $rivi['osoite'],
                'kuvaus' => $rivi['kuvaus'],
                'arvo' => $rivi['arvo']
            ));
        }
        return $kohteet;
    }

    public static function etsi($kohdeid) {
        $kysely = DB::connection()->prepare('SELECT * FROM Kohde WHERE kohdeid = :kohdeid LIMIT 1');
        $kysely->execute(array('kohdeid' => $kohdeid));
        $rivi = $kysely->fetch();

        if ($rivi) {
            $kohde = new Kohde(array(
                'kohdeid' => $rivi['kohdeid'],
                'nimi' => $rivi['nimi'],
                'osoite' => $rivi['osoite'],
                'kuvaus' => $rivi['kuvaus'],
                'arvo' => $rivi['arvo']
            ));
            return $kohde;
        }
        return null;
    }

    public function tallenna() {
        $kysely = DB::connection()->prepare('INSERT INTO Kohde (nimi, osoite, kuvaus, arvo) VALUES (:nimi, :osoite, :kuvaus, :arvo) RETURNING kohdeid');
        $kysely->execute(array('nimi' => $this->nimi, 'osoite' => $this->osoite, 'kuvaus' => $this->kuvaus, 'arvo' => $this->arvo));
        $rivi = $kysely->fetch();
        $this->kohdeid = $rivi['kohdeid'];
    }
    
    public function paivita() {
        $kysely = DB::connection()->prepare('UPDATE Kohde SET nimi = :nimi, osoite = :osoite, kuvaus = :kuvaus, arvo = :arvo WHERE kohdeid = :kohdeid');
        $kysely->execute(array('kohdeid' => $this->kohdeid, 'nimi' => $this->nimi, 'osoite' => $this->osoite, 'kuvaus' => $this->kuvaus, 'arvo' => $this->arvo));
    }
    
    public function voiko_poistaa() {
        // kohteen voi poistaa vain, jos kyseiseen kohteeseen ei ole keikka
        $kysely = DB::connection()->prepare('SELECT nimi FROM Keikka WHERE kohdeid = :kohdeid');
        $kysely->execute(array('kohdeid' => $this->kohdeid));
        $rivi = $kysely->fetch();
        if($rivi) {
            return FALSE;
        }
        return TRUE;
    }
    
    public function poista() {
        $kysely = DB::connection()->prepare('DELETE FROM Kohde WHERE kohdeid = :kohdeid');
        $kysely->execute(array('kohdeid' => $this->kohdeid));
    }

    public function validoi_nimi() {
        $virheet = array();
        if (is_numeric($this->nimi)) {
            $virheet[] = 'Kohteen nimenä ei voi olla numero!';
        } elseif ($this->nimi == '' || !$this->merkkijono_tarpeeksi_pitka($this->nimi, 4) || !$this->merkkijono_tarpeeksi_lyhyt($this->nimi, 30)) {
            $virheet[] = 'Kohteella tulee olla nimi, joka on 4-30 merkkiä pitkä!';
        }
        return $virheet;
    }

    public function validoi_arvo() {
        $virheet = array();
        if ($this->arvo == '') {
            $virheet[] = 'Arvio ryöstösaaliista ei voi olla tyhjä!';
        } elseif (!is_numeric($this->arvo) || !ctype_digit($this->arvo)) {
            $virheet[] = 'Kohteen arvo tulee ilmaista positiivisella kokonaisluvulla!';
        } elseif ($this->arvo < 100) {
            $virheet[] = 'Kohde ei ole ryöstämisen arvoinen jos saalis on alle 100!';
        }
        return $virheet;
    }

    public function validoi_kuvaus() {
        $virheet = array();
        if (is_numeric($this->kuvaus)) {
            $virheet[] = 'Kuvaus ei voi olla pelkästään numeerinen!';
        } elseif (!$this->merkkijono_tarpeeksi_lyhyt($this->kuvaus, 500)) {
            $virheet[] = 'Kuvaus voi olla korkeintaan 500 merkkiä pitkä!';
        }
        return $virheet;
    }

    public function validoi_osoite() {
        $virheet = array();
        if (is_numeric($this->osoite)) {
            $virheet[] = 'Osoite ei voi olla pelkästään numeerinen!';
        } elseif ($this->osoite != '' && (!$this->merkkijono_tarpeeksi_pitka($this->osoite, 4) || !$this->merkkijono_tarpeeksi_lyhyt($this->osoite, 50))) {
            $virheet[] = 'Osoitteen tulee olla 4-50 merkkiä pitkä!';
        }
        return $virheet;
    }

}
