<?php

class KeikkaController extends BaseController {

    public static function index($viesti, $virhe) {
        $keikat = Keikka::kaikki();
        self::count_uudet_viestit();
        foreach($keikat as $keikka) {
            $keikka->lisaa_ilmoittautumistieto();
            $keikka->lisaa_oma_ilmoittautumistieto($_SESSION['karhuid']);
        }
        View::make('keikka/keikat.html', array('keikat' => $keikat, 'viesti' => $viesti, 'virhe' => $virhe));
    }
    
    public static function uusi($kohdeid) {
        $kohteet = Kohde::kaikki();
        $karhut = Karhu::kaikki();
        $roolit = Rooli::kaikki();
        if($kohdeid) {
            $valittu_kohde = Kohde::etsi($kohdeid);
            View::make('keikka/uusi.html', array('kohteet' => $kohteet, 'karhut' => $karhut, 'valittu_kohde' => $valittu_kohde, 'roolit' => $roolit));
        } else {
            View::make('keikka/uusi.html', array('kohteet' => $kohteet, 'karhut' => $karhut, 'roolit' => $roolit));
        }
    }

    public static function nayta($keikkaid) {
        $keikka = Keikka::etsi($keikkaid);
        View::make('keikka/keikka.html', array('keikka' => $keikka));
    }

    public static function lisaa() {
        $parametrit = $_POST;
        $valittu_kohdeid = $parametrit['kohde'];
        $valittu_karhuid = $parametrit['karhu'];
        $roolit = Rooli::kaikki();
        
        $rosvoporukka = null;
        
        foreach($roolit as $rooli) {
            $rosvoporukka[] = array(
                'tehtava' => $rooli->nimi,
                'lukumaara' => $parametrit[$rooli->nimi]);
        }
        
        $attribuutit = array(
            'nimi' => $parametrit['nimi'],
            'osallistujamaara' => $parametrit['osallistujamaara'],
            'kohdeid' => $valittu_kohdeid,
            'karhuid' => $valittu_karhuid
        );
                        
        $keikka = new Keikka($attribuutit);
        
        $virheet = $keikka->virheet();
        if (count($virheet) == 0) {
            $keikka->tallenna();
            Redirect::to('/keikat/' . $keikka->keikkaid, array('viesti' => 'Uusi keikka lisätty'));
        } else {
            $kohteet = Kohde::kaikki();
            $karhut = Karhu::kaikki();
            $valittu_kohde = Kohde::etsi($valittu_kohdeid);
            $valittu_karhu = Karhu::etsi($valittu_karhuid);
            View::make('keikka/uusi.html', array('kohteet' => $kohteet, 'karhut' => $karhut, 'virheet' => $virheet, 'attribuutit' => $attribuutit, 'valittu_kohde' => $valittu_kohde, 'valittu_karhu' => $valittu_karhu, 'roolit' => $roolit, 'rosvoporukka' => $rosvoporukka));
        }
    }
    
    public static function ilmoittaudu($keikkaid) {
        $keikka = Keikka::etsi($keikkaid);
        $karhuid = $_SESSION['karhuid'];
        if(Karhu::onko_karhu_keikalla($karhuid, $keikkaid)) {
            Redirect::to('/keikat', array('viesti' => null, 'virhe' => 'Et voi ilmoittautua uudestaan samalle keikalle!'));
        } elseif($keikka->onko_keikalla_tilaa()) {
            Keikka::ilmoittaudu($keikkaid, $karhuid);
            Redirect::to('/keikat', array('viesti' => 'Ilmoittautuminen lisätty!', 'virhe' => null));
        } else {
            Redirect::to('/keikat', array('viesti' => null, 'virhe' => 'Keikalle ei mahdu enempää, se on täynnä!'));
        }   
    }
    
    public static function peru_ilmoittautuminen($keikkaid) {
        $keikka = Keikka::etsi($keikkaid);
        $karhuid = $_SESSION['karhuid'];
        if(Karhu::onko_karhu_keikalla($karhuid, $keikkaid)) {
            Keikka::peru_osallistuminen($keikkaid, $karhuid);
            Redirect::to('/keikat', array('viesti' => 'Ilmoittautuminen peruttu!', 'virhe' => null));
        } else {
            Redirect::to('/keikat', array('viesti' => null, 'virhe' => 'Et voi perua ilmoittautumista koska et ole kyseisellä keikalla!'));
        }
        
    }

}
