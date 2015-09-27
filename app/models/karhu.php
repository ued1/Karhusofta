<?php

class Karhu extends BaseModel {

    public $karhuid, $salasana, $nimi, $saldo, $pvm, $taidot;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validoi_nimen_pituus', 'validoi_salasanan_pituus');
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
                'salasana' => $rivi['salasana'],
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
    
    public function paivita() {
        $kysely = DB::connection()->prepare('UPDATE Karhu SET nimi = :nimi, salasana = :salasana WHERE karhuid = :karhuid');
        $kysely->execute(array('karhuid' => $this->karhuid, 'nimi' => $this->nimi, 'salasana' => $this->salasana));
    }
    
    public function voiko_poistaa() {
        // karhun voi poistaa, jos hän ei ole meneillään olevalla keikalla ryhmänjohtajana
        $kysely = DB::connection()->prepare('SELECT nimi FROM Keikka WHERE karhuid = :karhuid');
        $kysely->execute(array('karhuid' => $this->karhuid));
        $rivi = $kysely->fetch();
        if($rivi) {
            return FALSE;
        }
        return TRUE;
    }
    
    public function poista() {
        $kysely = DB::connection()->prepare('DELETE FROM Karhu WHERE karhuid = :karhuid');
        $kysely->execute(array('karhuid' => $this->karhuid));
    }
    
    public static function tunnistaudu($tunnus, $salasana) {
        $kysely = DB::connection()->prepare('SELECT * FROM Karhu WHERE nimi = :tunnus AND salasana = :salasana LIMIT 1');
        $kysely->execute(array('tunnus' => $tunnus, 'salasana' => $salasana));
        $rivi = $kysely->fetch();
        if($rivi) {
            $karhu = new Karhu($rivi);
            return $karhu;
        } else {
            return null;
        }
    }

    public function validoi_nimen_pituus() {
        $virheet = array();
        if ($this->nimi == '') {
            $virheet[] = 'Karhulla tulee olla nimi!';
        } elseif (!$this->merkkijono_tarpeeksi_pitka($this->nimi, 2) || !$this->merkkijono_tarpeeksi_lyhyt($this->nimi, 20)) {
            $virheet[] = 'Karhun nimen tulee olla 2-20 merkkiä pitkä!';
        }
        return $virheet;
    }

    public function validoi_salasanan_pituus() {
        $virheet = array();
        if ($this->salasana == '') {
            $virheet[] = 'Karhulla tulee olla salasana!';
        } elseif (!$this->merkkijono_tarpeeksi_pitka($this->salasana, 5) || !$this->merkkijono_tarpeeksi_lyhyt($this->salasana, 20)) {
            $virheet[] = 'Karhun salasanan tulee olla 5-20 merkkiä pitkä!';
        }
        return $virheet;
    }

}
