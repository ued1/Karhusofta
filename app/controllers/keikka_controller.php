<?php

class KeikkaController extends BaseController {

    public static function index() {
        $keikat = Keikka::kaikki();
        View::make('keikka/keikat.html', array('keikat' => $keikat));
    }
    
    public static function uusi() {
        $kohteet = Kohde::kaikki();
        $karhut = Karhu::kaikki();
        View::make('keikka/uusi.html', array('kohteet' => $kohteet, 'karhut' => $karhut));
    }
    
    public static function nayta($keikkaid) {
        $keikka = Keikka::etsi($keikkaid);
        View::make('keikka/keikka.html', array('keikka' => $keikka));
    }
    
    public static function lisaa() {
        $parametrit = $_POST;
        $keikka = new Keikka(array(
            'nimi' => $parametrit['nimi'],
            'osallistujamaara' => $parametrit['osallistujamaara'],
            'kohdeid' => $parametrit['kohdeid'],
            'karhuid' => $parametrit['karhuid'],
        ));
        $keikka->tallenna();
        Redirect::to('/keikat/' . $keikka->keikkaid, array('viesti' => 'Uusi keikka lisätty'));
    }
    
}
