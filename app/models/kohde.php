<?php

class Kohde extends BaseModel {

    public $kohdeid, $nimi, $osoite, $kuvaus, $arvo;

    public function __construct($attributes) {
        parent::__construct($attributes);
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

}
