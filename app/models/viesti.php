<?php

class Viesti extends BaseModel {

    public $viestiid, $lahetysaika, $lukemisaika, $lahettajaid, $lahettajanimi, $saajaid, $saajanimi, $otsikko, $viesti;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validoi_otsikko', 'validoi_sisalto', 'validoi_vastaanottaja');
    }

    public static function saapuneet($karhuid) {
        $kysely = DB::connection()->prepare("SELECT viestiid, to_char(lahetysaika, 'YYYY-MM-DD HH24:MI') as lahetetty, to_char(lukemisaika, 'YYYY-MM-DD HH24:MI') as luettu, lahettajaid, otsikko, viesti FROM Viesti WHERE saajaid = :saajaid ORDER BY lahetetty DESC");
        $kysely->execute(array('saajaid' => $karhuid));
        $rivit = $kysely->fetchAll();
        $saapuneet = array();
        foreach ($rivit as $rivi) {
            $saapuneet[] = new Viesti(array(
                'viestiid' => $rivi['viestiid'],
                'lahetysaika' => $rivi['lahetetty'],
                'lukemisaika' => $rivi['luettu'],
                'lahettajaid' => $rivi['lahettajaid'],
                'lahettajanimi' => Karhu::etsi($rivi['lahettajaid'])->nimi,
                'saajaid' => $karhuid,
                'saajanimi' => Karhu::etsi($karhuid)->nimi,
                'otsikko' => $rivi['otsikko'],
                'viesti' => $rivi['viesti']
            ));
        }
        return $saapuneet;
    }
    
    public static function uudetviestit($karhuid) {
        $kysely = DB::connection()->prepare("SELECT viestiid, to_char(lahetysaika, 'YYYY-MM-DD HH24:MI') as lahetetty, lahettajaid, otsikko, viesti FROM Viesti WHERE saajaid = :saajaid AND lukemisaika is null ORDER BY lahetetty DESC");
        $kysely->execute(array('saajaid' => $karhuid));
        $rivit = $kysely->fetchAll();
        $uudet = array();
        foreach ($rivit as $rivi) {
            $uudet[] = new Viesti(array(
                'viestiid' => $rivi['viestiid'],
                'lahetysaika' => $rivi['lahetetty'],
                'lahettajaid' => $rivi['lahettajaid'],
                'lahettajanimi' => Karhu::etsi($rivi['lahettajaid'])->nimi,
                'otsikko' => $rivi['otsikko'],
                'viesti' => $rivi['viesti']
            ));
        }
        return $uudet;
    }
    
    public static function laske_uudet_viestit($karhuid) {
        $kysely = DB::connection()->prepare('SELECT count(*) as lkm FROM Viesti WHERE saajaid = :saajaid AND lukemisaika is null LIMIT 1');
        $kysely->execute(array('saajaid' => $karhuid));
        $rivi = $kysely->fetch();
        return $rivi['lkm'];
    }
    
    public static function etsi($viestiid) {
        $kysely = DB::connection()->prepare("SELECT viestiid, to_char(lahetysaika, 'YYYY-MM-DD HH24:MI') as lahetetty, to_char(lukemisaika, 'YYYY-MM-DD HH24:MI') as luettu, saajaid, lahettajaid, otsikko, viesti FROM Viesti WHERE viestiid = :viestiid LIMIT 1");
        $kysely->execute(array('viestiid' => $viestiid));
        $rivi = $kysely->fetch();
        if ($rivi) {
            $viesti = new Viesti(array(
                'viestiid' => $rivi['viestiid'],
                'lahetysaika' => $rivi['lahetetty'],
                'lukemisaika' => $rivi['luettu'],
                'lahettajaid' => $rivi['lahettajaid'],
                'lahettajanimi' => Karhu::etsi($rivi['lahettajaid'])->nimi,
                'saajaid' => $rivi['saajaid'],
                'saajanimi' => Karhu::etsi($rivi['saajaid'])->nimi,
                'otsikko' => $rivi['otsikko'],
                'viesti' => $rivi['viesti']
            ));
            return $viesti;
        }
        return null;
    }
    
    public static function lahetetyt($karhuid) {
        $kysely = DB::connection()->prepare("SELECT viestiid, to_char(lahetysaika, 'YYYY-MM-DD HH24:MI') as lahetetty, to_char(lukemisaika, 'YYYY-MM-DD HH24:MI') as luettu, saajaid, otsikko, viesti FROM Viesti WHERE lahettajaid = :lahettajaid ORDER BY lahetetty DESC");
        $kysely->execute(array('lahettajaid' => $karhuid));
        $rivit = $kysely->fetchAll();
        $saapuneet = array();
        foreach ($rivit as $rivi) {
            $saapuneet[] = new Viesti(array(
                'viestiid' => $rivi['viestiid'],
                'lahetysaika' => $rivi['lahetetty'],
                'lukemisaika' => $rivi['luettu'],
                'lahettajaid' => $karhuid,
                'lahettajanimi' => Karhu::etsi($karhuid)->nimi,
                'saajaid' => $rivi['saajaid'],
                'saajanimi' => Karhu::etsi($rivi['saajaid'])->nimi,
                'otsikko' => $rivi['otsikko'],
                'viesti' => $rivi['viesti']
            ));
        }
        return $saapuneet;
    }
        
    public function tallenna($karhuid) {
        $kysely = DB::connection()->prepare('INSERT INTO Viesti (lahetysaika, lahettajaid, saajaid, otsikko, viesti) VALUES (now(), :karhuid, :saajaid, :otsikko, :viesti)');
        $kysely->execute(array('karhuid' => $karhuid, 'saajaid' => $this->saajaid, 'otsikko' => $this->otsikko, 'viesti' => $this->viesti));
    }
            
    public function poista($karhuid) {
        $poistettava = Viesti::etsi($this->viestiid);
        if($poistettava->saajaid == $karhuid) {
            $kysely = DB::connection()->prepare('DELETE FROM Viesti WHERE viestiid = :viestiid');
            $kysely->execute(array('viestiid' => $this->viestiid));
            return TRUE;
        }
        return FALSE;
    }
    
    public function onko_lukuoikeus($karhuid) {
        if($this->lahettajaid == $karhuid || $this->saajaid == $karhuid) {
            return TRUE;
        }
        return FALSE;
    }
    
    public function aseta_luetuksi() {
        $kysely = DB::connection()->prepare('UPDATE Viesti SET lukemisaika = now() WHERE viestiid = :viestiid');
        $kysely->execute(array('viestiid' => $this->viestiid));
    }

    public function validoi_otsikko() {
        $virheet = array();
        if ($this->otsikko == null || $this->otsikko == '' || strlen($this->otsikko) > 30) {
            $virheet[] = 'Otsikon tulee olla 1-30 merkkiä pitkä!';
        }
        return $virheet;
    }
    
    public function validoi_sisalto() {
        $virheet = array();
        if ($this->viesti == null || $this->viesti == '' || strlen($this->viesti) > 500) {
            $virheet[] = 'Viestin tulee olla 1-500 merkkiä pitkä!';
        }
        return $virheet;
    }
    
    public function validoi_vastaanottaja() {
        $virheet = array();
        if($this->saajaid == null || $this->saajaid == 0) {
            $virheet[] = 'Viestillä tulee olla vastaanottaja!';
        } else if(Karhu::etsi($this->saajaid) == null) {
            $virheet[] = 'Vastaanottajaa ei ole olemassa!';
        }
        return $virheet;
    }
            


}
