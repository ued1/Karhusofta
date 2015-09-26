<?php

class KarhuController extends BaseController {

    public static function index() {
        $karhut = Karhu::kaikki();
        View::make('karhu/karhut.html', array('karhut' => $karhut));
    }

    public static function uusi() {
        View::make('karhu/uusi.html');
    }
    
    public static function muokkaa($karhuid) {
        View::make('karhu/muokkaus.html');
    }
    
    public static function nayta($karhuid) {
        $karhu = Karhu::etsi($karhuid);
        View::make('karhu/karhu.html', array('karhu' => $karhu));
    }
    
    public static function lisaa() {
        $parametrit = $_POST;
        $attribuutit = array(
            'nimi' => $parametrit['nimi'],
            'salasana' => $parametrit['salasana'],
            'saldo' => 0
        );
        $karhu = new Karhu($attribuutit);
        $virheet = $karhu->virheet();
        if(count($virheet) == 0) {
            $karhu->tallenna();
            Redirect::to('/karhut/' . $karhu->karhuid, array('viesti' => 'Uusi karhu lisätty'));
        } else {
            View::make('karhu/uusi.html', array('virheet' => $virheet, 'attribuutit' => $attribuutit));
        }
    }
    
    
    
}
