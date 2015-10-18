<?php

class Rooli extends BaseModel {

    public $rooliid, $nimi, $kuvaus, $vaativuuskerroin, $maksimimaara;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validoi_nimi', 'validoi_kuvaus', 'validoi_vaativuuskerroin', 'validoi_maksimimaara');
    }

    public static function kaikki() {
        $kysely = DB::connection()->prepare('SELECT * FROM Rooli');
        $kysely->execute();
        $rivit = $kysely->fetchAll();
        $roolit = array();

        foreach ($rivit as $rivi) {
            $roolit[] = new Rooli(array(
                'rooliid' => $rivi['rooliid'],
                'nimi' => $rivi['nimi'],
                'kuvaus' => $rivi['kuvaus'],
                'vaativuuskerroin' => $rivi['vaativuuskerroin'],
                'maksimimaara' => $rivi['maksimimaara']
            ));
        }
        return $roolit;
    }

    public static function etsi($rooliid) {
        $kysely = DB::connection()->prepare('SELECT * FROM Rooli WHERE rooliid = :rooliid LIMIT 1');
        $kysely->execute(array('rooliid' => $rooliid));
        $rivi = $kysely->fetch();

        if ($rivi) {
            $rooli = new Rooli(array(
                'rooliid' => $rivi['rooliid'],
                'nimi' => $rivi['nimi'],
                'kuvaus' => $rivi['kuvaus'],
                'vaativuuskerroin' => $rivi['vaativuuskerroin'],
                'maksimimaara' => $rivi['maksimimaara']
            ));
            return $rooli;
        }
        return null;
    }

    public function tallenna() {
        $kysely = DB::connection()->prepare('INSERT INTO Rooli (nimi, kuvaus, vaativuuskerroin, maksimimaara) VALUES (:nimi, :kuvaus, :vaativuuskerroin, :maksimimaara) RETURNING rooliid');
        $kysely->execute(array('nimi' => $this->nimi, 'kuvaus' => $this->kuvaus, 'vaativuuskerroin' => $this->vaativuuskerroin, 'maksimimaara' => $this->maksimimaara));
        $rivi = $kysely->fetch();
        $this->rooliid = $rivi['rooliid'];
    }

    public function paivita() {
        $kysely = DB::connection()->prepare('UPDATE Rooli SET nimi = :nimi, kuvaus = :kuvaus, vaativuuskerroin = :vaativuuskerroin, maksimimaara = :maksimimaara WHERE rooliid = :rooliid');
        $kysely->execute(array('rooliid' => $this->rooliid, 'nimi' => $this->nimi, 'kuvaus' => $this->kuvaus, 'vaativuuskerroin' => $this->vaativuuskerroin, 'maksimimaara' => $this->maksimimaara));
    }

    public static function poista($rooliid) {
        $kysely = DB::connection()->prepare('DELETE FROM Rooli WHERE rooliid = :rooliid');
        $kysely->execute(array('rooliid' => $rooliid));
    }

    public static function karhun_taidot($karhuid) {
        $kysely = DB::connection()->prepare('SELECT rooli.rooliid, rooli.nimi FROM Rooli, Osaaminen WHERE karhuid = :karhuid AND rooli.rooliid = osaaminen.rooliid');
        $kysely->execute(array('karhuid' => $karhuid));
        $rivit = $kysely->fetchAll();
        $taidot = array();

        foreach ($rivit as $rivi) {
            $taidot[] = new Rooli(array(
                'rooliid' => $rivi['rooliid'],
                'nimi' => $rivi['nimi']
            ));
        }
        return $taidot;
    }

    public static function lisaa_karhulle_roolit($karhuid, $roolit) {
        foreach ($roolit as $rooliid) {
            $kysely = DB::connection()->prepare('INSERT INTO Osaaminen (karhuid, rooliid) VALUES (:karhuid, :rooliid)');
            $kysely->execute(array('karhuid' => $karhuid, 'rooliid' => $rooliid));
        }
    }

    public static function muokkaa_karhun_rooleja($karhuid, $roolit) {
        $kysely = DB::connection()->prepare('DELETE FROM Osaaminen WHERE karhuid = :karhuid');
        $kysely->execute(array('karhuid' => $karhuid));
        self::lisaa_karhulle_roolit($karhuid, $roolit);
    }

    public function validoi_nimi() {
        $virheet = array();
        if (is_numeric($this->nimi)) {
            $virheet[] = 'Roolin nimenä ei voi olla numero!';
        } elseif ($this->nimi == '' || !$this->merkkijono_tarpeeksi_pitka($this->nimi, 4) || !$this->merkkijono_tarpeeksi_lyhyt($this->nimi, 20)) {
            $virheet[] = 'Roolin nimen tulee olla 4-20 merkkiä pitkä!';
        }
        return $virheet;
    }

    public function validoi_vaativuuskerroin() {
        $virheet = array();
        if ($this->vaativuuskerroin == '') {
            $virheet[] = 'Vaativuuskerroin ei voi olla tyhjä!';
        } elseif (!is_numeric($this->vaativuuskerroin) || !ctype_digit($this->vaativuuskerroin)) {
            $virheet[] = 'Vaativuuskertoimen tulee olla positiivinen kokonaisluvu, joka on vähintään 5 ja enintään 10.';
        } elseif ($this->vaativuuskerroin < 5 || $this->vaativuuskerroin > 10) {
            $virheet[] = 'Vaativuuskertoimen tulee olla vähintään 5 ja enintään 10!';
        }
        return $virheet;
    }

    public function validoi_maksimimaara() {
        $virheet = array();
        if ($this->maksimimaara == '') {
            $virheet[] = 'Maksimimäärä ei voi olla tyhjä!';
        } elseif (!is_numeric($this->maksimimaara) || !ctype_digit($this->maksimimaara) || $this->maksimimaara < 1) {
            $virheet[] = 'Maksimimäärän tulee olla positiivinen kokonaisluvu, joka on vähintään 1.';
        } else if($this->maksimimaara > 10000) {
            $virheet[] = 'Maksimimäärä ei ole realistinen...';
        }
        return $virheet;
    }

    public function validoi_kuvaus() {
        $virheet = array();
        if (is_numeric($this->kuvaus)) {
            $virheet[] = 'Kuvaus ei voi olla pelkästään numeerinen!';
        } elseif (!$this->merkkijono_tarpeeksi_lyhyt($this->kuvaus, 500)) {
            $virheet[] = 'Kuvaus voi olla korkeintaan 120 merkkiä pitkä!';
        }
        return $virheet;
    }

}
