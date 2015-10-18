<?php

class KarhuController extends BaseController {

    public static function index() {
        $karhut = Karhu::kaikki();
        View::make('karhu/karhut.html', array('karhut' => $karhut));
    }

    public static function uusi() {
        $roolit = Rooli::kaikki();
        View::make('karhu/uusi.html', array('roolit' => $roolit));
    }

    public static function muokkaa($karhuid) {
        $karhu = Karhu::etsi($karhuid);
        $roolit = Rooli::kaikki();
        $karhu_roolit = Rooli::karhun_taidot($karhuid);
        $valitut_roolit = array();
        foreach ($karhu_roolit as $rooli) {
            $valitut_roolit[] = $rooli->rooliid;
        }
        View::make('karhu/muokkaus.html', array('attribuutit' => $karhu, 'roolit' => $roolit, 'valitut_roolit' => $valitut_roolit));
    }

    public static function nayta($karhuid) {
        $karhu = Karhu::etsi($karhuid);
        View::make('karhu/karhu.html', array('karhu' => $karhu));
    }

    public static function lisaa() {
        $parametrit = $_POST;
        $valitut_roolit = array();
        if (isset($parametrit['valitut_roolit'])) {
            $valitut_roolit = $parametrit['valitut_roolit'];
        }
        $attribuutit = array('nimi' => $parametrit['nimi'],
            'tunnus' => $parametrit['tunnus'],
            'salasana' => $parametrit['salasana'],
            'saldo' => 0
        );
        $karhu = new Karhu($attribuutit);
        $virheet = $karhu->virheet();
        if (count($virheet) == 0) {
            $karhu->tallenna();
            Rooli::lisaa_karhulle_roolit($karhu->karhuid, $valitut_roolit);
            Redirect::to('/karhut/' . $karhu->karhuid, array('viesti' => 'Uusi karhu lisätty'));
        } else {
            $roolit = Rooli::kaikki();
            View::make('karhu/uusi.html', array('virheet' => $virheet, 'attribuutit' => $attribuutit, 'roolit' => $roolit, 'valitut_roolit' => $valitut_roolit));
        }
    }

    public static function paivita($karhuid) {
        $parametrit = $_POST;
        $valitut_roolit = array();
        if (isset($parametrit['valitut_roolit'])) {
            $valitut_roolit = $parametrit['valitut_roolit'];
        }
        $attribuutit = array('karhuid' => $karhuid,
            'nimi' => $parametrit['nimi'],
            'tunnus' => $parametrit['tunnus'],
            'salasana' => $parametrit['salasana']
        );
        $karhu = new Karhu($attribuutit);
        $virheet = $karhu->virheet();
        if (count($virheet) == 0) {
            $karhu->paivita();
            Rooli::muokkaa_karhun_rooleja($karhu->karhuid, $valitut_roolit);
            Redirect::to('/karhut/' . $karhuid, array('viesti' => 'Karhua on muokattu onnistuneesti!'));
        } else {
            $roolit = Rooli::kaikki();
            View::make('karhu/muokkaus.html', array('virheet' => $virheet, 'attribuutit' => $attribuutit, 'roolit' => $roolit, 'valitut_roolit' => $valitut_roolit));
        }
    }

    public static function poista($karhuid) {
        $karhu = new Karhu(array('karhuid' => $karhuid));
        $alkuperainen_karhu = Karhu::etsi($karhuid);
        if (self::get_user_logged_in()->karhuid == $karhuid) {
            View::make('karhu/karhu.html', array('karhu' => $alkuperainen_karhu, 'virhe' => 'Et voi poistaa itseäsi!'));
        } elseif ($karhu->voiko_poistaa()) {
            $karhu->poista();
            Redirect::to('/karhut', array('viesti' => 'Karhu poistettu onnistuneesti!'));
        } else {
            View::make('karhu/karhu.html', array('karhu' => $alkuperainen_karhu, 'virhe' => 'Karhua ei voi poistaa, koska se on ryhmänjohtajana meneillään olevassa keikassa.'));
        }
    }

}
