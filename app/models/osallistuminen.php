<?php

class Osallistuminen {

    public $keikkaid, $karhuid, $karhunimi, $rooliid, $roolinimi, $vaativuuskerroin, $palkka;

    public static function osallistumistiedot($keikkaid) {
        $kysely = DB::connection()->prepare("SELECT keikkaid, osallistuminen.karhuid, karhu.nimi, osallistuminen.rooliid, case when osallistuminen.rooliid is null then 'Jokapaikanhöylä' else (select rooli.nimi from rooli where osallistuminen.rooliid = rooli.rooliid) end as roolinimi, case when osallistuminen.rooliid is null then '5' else (select rooli.vaativuuskerroin from rooli where osallistuminen.rooliid = rooli.rooliid) end as vaativuuskerroin FROM Osallistuminen, Karhu WHERE osallistuminen.karhuid = karhu.karhuid AND keikkaid = :keikkaid");
        $kysely->execute(array('keikkaid' => $keikkaid));
        $rivit = $kysely->fetchAll();
        $osallistumiset = array();
        foreach ($rivit as $rivi) {
            $osallistumiset[] = array(
                'keikkaid' => $rivi['keikkaid'],
                'karhuid' => $rivi['karhuid'],
                'nimi' => $rivi['nimi'],
                'rooliid' => $rivi['rooliid'],
                'roolinimi' => $rivi['roolinimi'],
                'vaativuuskerroin' => $rivi['vaativuuskerroin']
            );
        }
        return $osallistumiset;
    }
    /*
    public static function karhun_osallistumiset($karhuid) {
        $kysely = DB::connection()->prepare("SELECT keikka.nimi as keikkanimi, keikka.keikkaid, keikka.saalis, case when osallistuminen.rooliid is null then 'Jokapaikanhöylä' else (select rooli.nimi from rooli where osallistuminen.rooliid = rooli.rooliid) end as roolinimi, case when osallistuminen.rooliid is null then '5' else (select rooli.vaativuuskerroin from rooli where osallistuminen.rooliid = rooli.rooliid) end as vaativuuskerroin FROM Osallistuminen, Karhu WHERE osallistuminen.karhuid = karhu.karhuid AND karhu:karhuid = :karhuid");
        $kysely->execute(array('karhuid' => $karhuid));
        $rivit = $kysely->fetchAll();
        $osallistumiset = array();
        foreach ($rivit as $rivi) {
            $osallistumiset[] = array(
                'keikkaid' => $rivi['keikkaid'],
                'keikkanimi' => $rivi['keikkanimi'],
                'saalis' => $rivi['saalis'],
                'karhuid' => $rivi['karhuid'],
                'nimi' => $rivi['nimi'],
                'rooliid' => $rivi['rooliid'],
                'roolinimi' => $rivi['roolinimi'],
                'vaativuuskerroin' => $rivi['vaativuuskerroin']
            );
        }
        return $osallistumiset;
    }*/

}
