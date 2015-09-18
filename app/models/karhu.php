<?php

class Karhu extends BaseModel {

    public $karhuid, $nimi, $saldo, $pvm;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function kaikki() {
        $kysely = DB::connection()->prepare('SELECT * FROM Karhu');
        $kysely->execute();
        $rivit = $kysely->fetchAll();
        $karhut = array();

        foreach ($rivit as $rivi) {
            $karhut[] = new Karhu(array(
                'karhuid' => $rivi['karhuid'],
                'nimi' => $rivi['nimi'],
                'saldo' => $rivi['saldo'],
                'pvm' => $rivi['pvm']
            ));
        }
        return $karhut;
    }

    public static function etsi($karhuid) {
        $kysely = DB::connection()->prepare('SELECT * FROM Karhu WHERE karhuid = :karhuid LIMIT 1');
        $kysely->execute(array('karhuid' => $karhuid));
        $rivi = $kysely->fetch();

        if ($rivi) {
            $karhu = new Karhu(array(
                'karhuid' => $rivi['karhuid'],
                'nimi' => $rivi['nimi'],
                'saldo' => $rivi['saldo'],
                'pvm' => $rivi['pvm']
            ));
            return $karhu;
        }
        return null;
    }
    
}
