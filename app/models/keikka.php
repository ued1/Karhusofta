<?php

class Keikka extends BaseModel {

    public $keikkaid, $nimi, $osallistujamaara, $ilmoittautuneita, $kayttaja_keikalla, $kohdeid, $kohdenimi, $kohdearvo, $karhuid, $karhunimi;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validoi_nimi', 'validoi_osallistujamaara', 'validoi_valinnat');
    }

    public static function kaikki() {
        $kysely = DB::connection()->prepare('SELECT keikka.keikkaid, keikka.nimi, keikka.osallistujamaara, kohde.kohdeid, kohde.nimi AS kohdenimi, kohde.arvo FROM Keikka, Kohde WHERE keikka.kohdeid = kohde.kohdeid');
        $kysely->execute();
        $rivit = $kysely->fetchAll();
        $keikat = array();

        foreach ($rivit as $rivi) {
            $keikat[] = new Keikka(array(
                'keikkaid' => $rivi['keikkaid'],
                'nimi' => $rivi['nimi'],
                'osallistujamaara' => $rivi['osallistujamaara'],
                'kohdeid' => $rivi['kohdeid'],
                'kohdenimi' => $rivi['kohdenimi'],
                'kohdearvo' => $rivi['arvo']
            ));
        }
        return $keikat;
    }

    public static function etsi($keikkaid) {
        $kysely = DB::connection()->prepare('SELECT keikkaid, keikka.nimi, keikka.osallistujamaara, kohde.kohdeid, kohde.nimi AS kohdenimi, kohde.arvo, karhu.nimi AS karhunimi, karhu.karhuid FROM Keikka, Kohde, Karhu WHERE keikkaid = :keikkaid AND keikka.kohdeid = kohde.kohdeid AND keikka.karhuid = karhu.karhuid LIMIT 1');
        $kysely->execute(array('keikkaid' => $keikkaid));
        $rivi = $kysely->fetch();

        if ($rivi) {
            $keikka = new Keikka(array(
                'keikkaid' => $rivi['keikkaid'],
                'nimi' => $rivi['nimi'],
                'osallistujamaara' => $rivi['osallistujamaara'],
                'kohdeid' => $rivi['kohdeid'],
                'kohdenimi' => $rivi['kohdenimi'],
                'kohdearvo' => $rivi['arvo'],
                'karhunimi' => $rivi['karhunimi'],
                'karhuid' => $rivi['karhuid']
            ));
            return $keikka;
        }
        return null;
    }

    public static function onko_keikkaa_nimella($hakusana) {
        $kysely = DB::connection()->prepare('SELECT nimi FROM Keikka WHERE nimi = :hakusana');
        $kysely->execute(array('hakusana' => $hakusana));
        $rivi = $kysely->fetch();
        if ($rivi) {
            return TRUE;
        }
        return FALSE;
    }

    public function tallenna() {
        $kysely = DB::connection()->prepare('INSERT INTO Keikka (nimi, osallistujamaara, kohdeid, karhuid) VALUES (:nimi, :osallistujamaara, :kohdeid, :karhuid) RETURNING keikkaid');
        $kysely->execute(array('nimi' => $this->nimi, 'osallistujamaara' => $this->osallistujamaara, 'kohdeid' => $this->kohdeid, 'karhuid' => $this->karhuid));
        $rivi = $kysely->fetch();
        $this->keikkaid = $rivi['keikkaid'];
    }

    public function validoi_nimi() {
        $virheet = array();
        if ($this->nimi == '' || !$this->merkkijono_tarpeeksi_lyhyt($this->nimi, 50)) {
            $virheet[] = 'Keikalla tulee olla nimi, joka on 1-50 merkkiä pitkä!';
        } elseif ($this->onko_keikkaa_nimella($this->nimi)) {
            $virheet[] = "Keikka nimeltään $this->nimi on jo olemassa, valitse toinen nimi!";
        }
        return $virheet;
    }

    public function validoi_osallistujamaara() {
        $virheet = array();
        if (!is_numeric($this->osallistujamaara)) {
            $virheet[] = 'Osallistujamäärä tulee ilmaista positiivisella kokonaisluvulla!';
        } elseif ($this->osallistujamaara == '') {
            $virheet[] = 'Osallistujamäärä ei voi olla tyhjä!';
        } elseif (!ctype_digit($this->osallistujamaara)) {
            $virheet[] = 'Osallistujamäärän tulee olla kokonaisluku!';
        } elseif ($this->osallistujamaara < 2) {
            $virheet[] = 'Osallistujia täytyy olla vähintään kaksi!';
        }
        return $virheet;
    }

    public function validoi_valinnat() {
        $virheet = array();
        if ($this->kohdeid == '' || $this->kohdeid == 0) {
            $virheet[] = 'Keikalle täytyy valita kohde!';
        }
        if ($this->karhuid == '' || $this->karhuid == 0) {
            $virheet[] = 'Keikalle täytyy valita vastuukarhu!';
        }
        return $virheet;
    }

    public function onko_keikalla_tilaa() {
        if ($this->osallistujamaara > self::osallistujia($this->keikkaid)) {
            return TRUE;
        }
        return FALSE;
    }

    public static function osallistujia($keikkaid) {
        $kysely = DB::connection()->prepare('SELECT count(*) FROM Osallistuminen WHERE keikkaid = :keikkaid');
        $kysely->execute(array('keikkaid' => $keikkaid));
        $rivi = $kysely->fetch();
        return $rivi[0];
    }

    public function lisaa_ilmoittautumistieto() {
        $this->ilmoittautuneita = self::osallistujia($this->keikkaid);
    }

    public function lisaa_oma_ilmoittautumistieto($karhuid) {
        if (Karhu::onko_karhu_keikalla($karhuid, $this->keikkaid)) {
            $this->kayttaja_keikalla = TRUE;
        } else {
            $this->kayttaja_keikalla = FALSE;
        }
    }

    public static function osallistujat($keikkaid) {
        $kysely = DB::connection()->prepare('SELECT karhuid from Osallistuminen WHERE keikkaid = :keikkaid');
        $kysely->execute(array('keikkaid' => $keikkaid));
        $rivit = $kysely->fetchAll();
        $osallistujat = array();

        foreach ($rivit as $rivi) {
            $karhu = Karhu::etsi($rivi['karhuid']);
            $osallistujat[] = $karhu;
        }
        return $osallistujat;
    }

    public static function ilmoittaudu($keikkaid, $karhuid) {
        $kysely = DB::connection()->prepare('INSERT INTO Osallistuminen (keikkaid, karhuid) VALUES (:keikkaid, :karhuid)');
        $kysely->execute(array('keikkaid' => $keikkaid, 'karhuid' => $karhuid));
    }

    public static function peru_osallistuminen($keikkaid, $karhuid) {
        $kysely = DB::connection()->prepare('DELETE From Osallistuminen WHERE keikkaid = :keikkaid AND karhuid = :karhuid');
        $kysely->execute(array('keikkaid' => $keikkaid, 'karhuid' => $karhuid));
    }

}
