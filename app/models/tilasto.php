<?php

class Tilasto extends BaseModel {

    public static function luo_tilasto() {
        $keikat = Keikka::keikat_paattyneet();
        $tilasto = array();
        $tilasto['karhut'] = Karhu::lukumaara();
        $tilasto['onnistumisprosentti'] = self::laske_onnistumisprosentti($keikat);
        $tilasto['tuottavin_keikka'] = self::tuottavin_keikka();
        $tilasto['kokonaissaalis'] = self::kokonaissaalis();
        $tilasto['keikkoja'] = self::suoritettujen_keikkojen_lkm();
        $tilasto['topkarhut'] = self::top5_karhut_saldo();
        return $tilasto;
    }

    private static function tuottavin_keikka() {
        $kysely = DB::connection()->prepare('SELECT keikkaid, nimi, saalis FROM Keikka WHERE saalis is not null ORDER BY saalis DESC LIMIT 1');
        $kysely->execute();
        $rivi = $kysely->fetch();
        if ($rivi) {
            $keikka = new Keikka(array(
                'keikkaid' => $rivi['keikkaid'],
                'nimi' => $rivi['nimi'],
                'saalis' => $rivi['saalis']
            ));
            return $keikka;
        }
        return null;
    }

    private static function suoritettujen_keikkojen_lkm() {
        $kysely = DB::connection()->prepare('SELECT count(nimi) as summa FROM Keikka WHERE (suoritettu is not null OR saalis is not null)');
        $kysely->execute();
        $rivi = $kysely->fetch();
        return $rivi['summa'];
    }

    private static function kokonaissaalis() {
        $kysely = DB::connection()->prepare('SELECT sum(saalis) as summa FROM Keikka');
        $kysely->execute();
        $rivi = $kysely->fetch();
        return $rivi['summa'];
    }

    private static function laske_onnistumisprosentti($keikat) {
        $yhteensa = count($keikat);
        $negatiiviset = 0;
        if ($yhteensa == 0) {
            return "0%";
        }
        foreach ($keikat as $keikka) {
            if ($keikka->saalis == null || $keikka->saalis < 0) {
                $negatiiviset++;
            }
        }
        if ($negatiiviset == 0) {
            return "100%";
        }
        return round(($yhteensa - $negatiiviset) / $yhteensa * 100) . "%";
    }

    private static function top5_karhut_saldo() {
        $kysely = DB::connection()->prepare('SELECT karhuid, nimi, saldo FROM Karhu ORDER BY saldo DESC LIMIT 5');
        $kysely->execute();
        $rivit = $kysely->fetchAll();
        $karhut = array();
        foreach ($rivit as $rivi) {
            $karhut[] = new Karhu(array(
                'karhuid' => $rivi['karhuid'],
                'nimi' => $rivi['nimi'],
                'saldo' => $rivi['saldo']
            ));
        }
        return $karhut;
    }

}
