<?php

class Keikka extends BaseModel {

    public $keikkaid, $nimi, $osallistujamaara, $kohdeid, $kohdenimi, $kohdearvo, $karhuid, $karhunimi;

    public function __construct($attributes) {
        parent::__construct($attributes);
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

}
