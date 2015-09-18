<?php

class Karhu extends BaseModel {

    public $karhuid, $salasana, $nimi, $saldo, $pvm, $taidot;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function kaikki() {
        $kysely = DB::connection()->prepare('SELECT * FROM Karhu');
        $kysely->execute();
        $rivit = $kysely->fetchAll();
        $karhut = array();

        foreach ($rivit as $rivi) {
            $t = Rooli::karhun_taidot($rivi['karhuid']);
            $karhut[] = new Karhu(array(
                'karhuid' => $rivi['karhuid'],
                'nimi' => $rivi['nimi'],
                'saldo' => $rivi['saldo'],
                'pvm' => $rivi['pvm'],
                'taidot' => $t
            ));
        }
        return $karhut;
    }

    public static function etsi($karhuid) {
        $kysely = DB::connection()->prepare('SELECT * FROM Karhu WHERE karhuid = :karhuid LIMIT 1');
        $kysely->execute(array('karhuid' => $karhuid));
        $rivi = $kysely->fetch();

        if ($rivi) {
            $t = Rooli::karhun_taidot($rivi['karhuid']);
            $karhu = new Karhu(array(
                'karhuid' => $rivi['karhuid'],
                'nimi' => $rivi['nimi'],
                'saldo' => $rivi['saldo'],
                'pvm' => $rivi['pvm'],
                'taidot' => $t
            ));
            
            return $karhu;
        }
        return null;
    }
    
    public static function lukumaara() {
        $kysely = DB::connection()->prepare('SELECT count(*) FROM Karhu');
        $kysely->execute();
        $rivi = $kysely->fetch();
        return $rivi[0];
    }
    
    public function tallenna() {
        $kysely = DB::connection()->prepare('INSERT INTO Karhu (nimi, salasana, saldo, pvm) VALUES (:nimi, :salasana, 0, now()::date) RETURNING karhuid');
        $kysely->execute(array('nimi' => $this->nimi, 'salasana' => $this->salasana));
        $rivi = $kysely->fetch();
        $this->karhuid = $rivi['karhuid'];
    }

}
