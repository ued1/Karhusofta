<?php

class Keikka extends BaseModel {

    public $keikkaid, $nimi, $osallistujamaara, $ilmoittautuneita, $kayttaja_keikalla, $kohdeid, $kohdenimi, $kohdearvo, $karhuid, $karhunimi, $rosvoporukka, $suoritettu, $kommentti, $saalis, $paikka;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validoi_nimi', 'validoi_osallistujamaara', 'validoi_valinnat', 'tarkista_osallistujat');
    }

    public static function kaikki() {
        $kysely = DB::connection()->prepare('SELECT keikka.keikkaid, keikka.nimi, keikka.osallistujamaara, kohde.kohdeid, kohde.nimi AS kohdenimi, kohde.arvo, karhuid, suoritettu, kommentti, saalis, paikka, johtaja FROM Keikka, Kohde WHERE keikka.kohdeid = kohde.kohdeid');
        $kysely->execute();
        $rivit = $kysely->fetchAll();
        $keikat = array();
        foreach ($rivit as $rivi) {
            $keikat[] = new Keikka(array(
                'keikkaid' => $rivi['keikkaid'],
                'nimi' => $rivi['nimi'],
                'osallistujamaara' => $rivi['osallistujamaara'],
                'kohdeid' => $rivi['kohdeid'],
                'karhuid' => $rivi['karhuid'],
                'kohdenimi' => $rivi['kohdenimi'],
                'kohdearvo' => $rivi['arvo'],
                'suoritettu' => $rivi['suoritettu'],
                'kommentti' => $rivi['kommentti'],
                'saalis' => $rivi['kommentti'],
                'paikka' => $rivi['paikka'],
                'johtaja' => $rivi['johtaja']
            ));
        }
        return $keikat;
    }

    public static function etsi($keikkaid) {
        $kysely = DB::connection()->prepare('SELECT keikkaid, keikka.nimi, keikka.osallistujamaara, kohde.kohdeid, kohde.nimi AS kohdenimi, kohde.arvo, karhu.nimi AS karhunimi, karhu.karhuid, suoritettu, kommentti, saalis, paikka, johtaja FROM Keikka, Kohde, Karhu WHERE keikkaid = :keikkaid AND keikka.kohdeid = kohde.kohdeid AND keikka.karhuid = karhu.karhuid LIMIT 1');
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
                'karhuid' => $rivi['karhuid'],
                'suoritettu' => $rivi['suoritettu'],
                'kommentti' => $rivi['kommentti'],
                'saalis' => $rivi['saalis'],
                'paikka' => $rivi['paikka'],
                'johtaja' => $rivi['johtaja']
            ));
            return $keikka;
        }
        return null;
    }
    
    public static function hae_vanha($keikkaid) {
        $kysely = DB::connection()->prepare('SELECT keikkaid, nimi, suoritettu, kommentti, saalis, paikka, johtaja FROM Keikka WHERE keikkaid = :keikkaid LIMIT 1');
        $kysely->execute(array('keikkaid' => $keikkaid));
        $rivi = $kysely->fetch();

        if ($rivi) {
            $keikka = new Keikka(array(
                'keikkaid' => $rivi['keikkaid'],
                'nimi' => $rivi['nimi'],
                'suoritettu' => $rivi['suoritettu'],
                'kommentti' => $rivi['kommentti'],
                'saalis' => $rivi['saalis'],
                'paikka' => $rivi['paikka'],
                'johtaja' => $rivi['johtaja']
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

    public function aloita($karhuid) {
        if ($this->karhuid == $karhuid) {
            $kysely = DB::connection()->prepare('UPDATE Keikka SET paikka = :kohdenimi WHERE keikkaid = :keikkaid');
            $kysely->execute(array('kohdenimi' => $this->kohdenimi, 'keikkaid' => $this->keikkaid));
            return TRUE;
        }
        return FALSE;
    }

    public function kirjaa_tulos($karhuid) {
        $kysely = DB::connection()->prepare('UPDATE Keikka SET suoritettu = now(), saalis = :saalis, kommentti = :kommentti, johtaja = (select nimi FROM Karhu WHERE karhuid = :karhuid LIMIT 1) WHERE keikkaid = :keikkaid');
        $kysely->execute(array('saalis' => $this->saalis, 'kommentti' => $this->kommentti, 'keikkaid' => $this->keikkaid, 'karhuid' => $karhuid));
    }
        
    public function tallenna($rooliid) {
        $kysely = DB::connection()->prepare('INSERT INTO Keikka (nimi, osallistujamaara, kohdeid, karhuid) VALUES (:nimi, :osallistujamaara, :kohdeid, :karhuid) RETURNING keikkaid');
        $kysely->execute(array('nimi' => $this->nimi, 'osallistujamaara' => $this->osallistujamaara, 'kohdeid' => $this->kohdeid, 'karhuid' => $this->karhuid));
        $rivi = $kysely->fetch();
        $this->keikkaid = $rivi['keikkaid'];
        self::ilmoittaudu($this->keikkaid, $this->karhuid, $rooliid);
    }
    
    public function poista() {
        $kysely = DB::connection()->prepare('DELETE FROM Keikka WHERE keikkaid = :keikkaid');
        $kysely->execute(array('keikkaid' => $this->keikkaid));
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
        } elseif ($this->osallistujamaara < 3) {
            $virheet[] = 'Osallistujia täytyy olla vähintään kolme!';
        }
        return $virheet;
    }

    public function validoi_tulos() {
        $virheet = array();
        if (!$this->saalis || !is_numeric($this->saalis)) {
            $virheet[] = 'Saalis tulee ilmaista kokonaisluvulla, joka on vähintään nolla.';
        } elseif ($this->saalis == '') {
            $virheet[] = 'Saalis ei voi olla tyhjä.';
        } elseif (!ctype_digit($this->saalis) || $this->saalis < 0) {
            $virheet[] = 'Saaliin tulee olla kokonaisluku, joka on vähintään nolla.';
        }
        if ($this->kommentti && !$this->merkkijono_tarpeeksi_lyhyt($this->kommentti, 100)) {
            $virheet[] = 'Kommentti voi olla korkeintaan 100 merkkiä pitkä.';
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

    public function tarkista_osallistujat() {
        $virheet = array();
        if ($this->rosvoporukka != null) {
            
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

    public function aseta_rosvoporukka($rosvoporukka) {
        $this->rosvoporukka = $rosvoporukka;
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

    public static function ilmoittaudu($keikkaid, $karhuid, $rooliid) {
        if($rooliid == 0) {
            $rooliid = NULL;
        }
        $kysely = DB::connection()->prepare('INSERT INTO Osallistuminen (keikkaid, karhuid, rooliid) VALUES (:keikkaid, :karhuid, :rooliid)');
        $kysely->execute(array('keikkaid' => $keikkaid, 'karhuid' => $karhuid, 'rooliid' => $rooliid));
    }

    public static function peru_osallistuminen($keikkaid, $karhuid) {
        $kysely = DB::connection()->prepare('DELETE From Osallistuminen WHERE keikkaid = :keikkaid AND karhuid = :karhuid');
        $kysely->execute(array('keikkaid' => $keikkaid, 'karhuid' => $karhuid));
    }

    public static function keikat_paattyneet() {
        $kysely = DB::connection()->prepare('SELECT keikkaid, nimi, osallistujamaara, paikka, suoritettu, kommentti, saalis FROM Keikka WHERE (suoritettu is not null OR kommentti is not null) ORDER BY suoritettu DESC');
        $kysely->execute();
        $rivit = $kysely->fetchAll();
        $keikat = array();

        foreach ($rivit as $rivi) {
            $keikat[] = new Keikka(array(
                'keikkaid' => $rivi['keikkaid'],
                'nimi' => $rivi['nimi'],
                'osallistujamaara' => $rivi['osallistujamaara'],
                'paikka' => $rivi['paikka'],
                'suoritettu' => $rivi['suoritettu'],
                'kommentti' => $rivi['kommentti'],
                'saalis' => $rivi['saalis']
            ));
        }
        return $keikat;
    }

    public static function keikat_ilmoittautuminen() {
        $kysely = DB::connection()->prepare('SELECT keikka.keikkaid, keikka.nimi, keikka.osallistujamaara, kohde.kohdeid, kohde.nimi AS kohdenimi, kohde.arvo, karhuid, paikka FROM Keikka, Kohde WHERE keikka.kohdeid = kohde.kohdeid AND suoritettu is null AND saalis is null');
        $kysely->execute();
        $rivit = $kysely->fetchAll();
        $keikat = array();

        foreach ($rivit as $rivi) {
            $keikat[] = new Keikka(array(
                'keikkaid' => $rivi['keikkaid'],
                'nimi' => $rivi['nimi'],
                'osallistujamaara' => $rivi['osallistujamaara'],
                'kohdeid' => $rivi['kohdeid'],
                'karhuid' => $rivi['karhuid'],
                'kohdenimi' => $rivi['kohdenimi'],
                'kohdearvo' => $rivi['arvo'],
                'paikka' => $rivi['paikka']
            ));
        }
        return $keikat;
    }

}
