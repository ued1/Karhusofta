<?php

class KeikkaController extends BaseController {

    public static function index() {
        self::listaa_keikat(null, null);
    }
    
    public static function listaa_keikat($viesti, $virhe) {
        $keikat = Keikka::kaikki();
        foreach($keikat as $keikka) {
            $keikka->lisaa_ilmoittautumistieto();
            $keikka->lisaa_oma_ilmoittautumistieto($_SESSION['karhuid']);
        }
        View::make('keikka/keikat.html', array('keikat' => $keikat, 'viesti' => $viesti, 'virhe' => $virhe));
    }
    
    public static function uusi($kohdeid) {
        $kohteet = Kohde::kaikki();
        $karhut = Karhu::kaikki();
        if($kohdeid) {
            $valittu_kohde = Kohde::etsi($kohdeid);
            View::make('keikka/uusi.html', array('kohteet' => $kohteet, 'karhut' => $karhut, 'valittu_kohde' => $valittu_kohde));
        } else {
            View::make('keikka/uusi.html', array('kohteet' => $kohteet, 'karhut' => $karhut));
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
            View::make('keikka/uusi.html', array('kohteet' => $kohteet, 'karhut' => $karhut, 'virheet' => $virheet, 'attribuutit' => $attribuutit, 'valittu_kohde' => $valittu_kohde, 'valittu_karhu' => $valittu_karhu));
        }
    }
    
    public static function ilmoittaudu($keikkaid) {
        $keikka = Keikka::etsi($keikkaid);
        $karhuid = $_SESSION['karhuid'];
        if(Karhu::onko_karhu_keikalla($karhuid, $keikkaid)) {
            self::listaa_keikat(null, 'Et voi ilmoittautua uudestaan samalle keikalle!');
        } elseif($keikka->onko_keikalla_tilaa()) {
            Keikka::ilmoittaudu($keikkaid, $karhuid);
            self::listaa_keikat('Ilmoittautuminen lisätty!', null);
        } else {
            self::listaa_keikat(null, 'Keikalle ei mahdu enempää, se on täynnä!');
        }   
    }

}
