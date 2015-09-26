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

}
