<?php

class Kassa extends BaseModel {

    public static function maksa_keikan_palkka($keikkaid) {
        $keikka = Keikka::etsi($keikkaid);
        $osallistumiset = self::osallistumistiedot($keikkaid);
        $osuudet = 4;
        foreach ($osallistumiset as $osallistuminen) {
            if ($osallistuminen['vaativuuskerroin']) {
                $osuudet = $osuudet + $osallistuminen['vaativuuskerroin'];
            } else {
                $osuudet = $osuudet + 5;
            }
        }
        $rahaa_jaljella = $keikka->saalis;
        foreach ($osallistumiset as $osallistuminen) {
            if ($osallistuminen['vaativuuskerroin']) {
                $palkka = self::maksa_karhun_palkka($osallistuminen['karhuid'], $keikka->saalis, $osuudet, $osallistuminen['vaativuuskerroin']);
            } else {
                $palkka = self::maksa_karhun_palkka($osallistuminen['karhuid'], $keikka->saalis, $osuudet, 5);
            }
            $rahaa_jaljella = $rahaa_jaljella - $palkka;
        }
        self::maksa_karhulle($keikka->karhuid, $rahaa_jaljella);
    }

    private static function maksa_karhun_palkka($karhuid, $saalis, $osuudet, $osuus) {
        $palkka = floor($saalis * $osuus / $osuudet);
        self::maksa_karhulle($karhuid, $palkka);
        return $palkka;
    }

    private static function maksa_karhulle($karhuid, $palkka) {
        $kysely = DB::connection()->prepare('UPDATE Karhu SET saldo = (SELECT saldo FROM Karhu WHERE karhuid = :karhuid) + :palkka WHERE karhuid = :karhuid');
        $kysely->execute(array('karhuid' => $karhuid, 'palkka' => $palkka));
    }

    private static function osallistumistiedot($keikkaid) {
        $kysely = DB::connection()->prepare('select karhuid, osallistuminen.rooliid, case when rooliid is null then null else (select vaativuuskerroin from rooli where osallistuminen.rooliid = rooli.rooliid) end as kerroin from osallistuminen where keikkaid = :keikkaid');
        $kysely->execute(array('keikkaid' => $keikkaid));
        $rivit = $kysely->fetchAll();
        $osallistumiset = array();
        foreach ($rivit as $rivi) {
            $osallistumiset[] = array(
                'karhuid' => $rivi['karhuid'],
                'rooliid' => $rivi['rooliid'],
                'vaativuuskerroin' => $rivi['kerroin']
            );
        }
        return $osallistumiset;
    }

}
