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
        $karhu = new Karhu(array(
            'nimi' => $parametrit['nimi'],
            'salasana' => $parametrit['salasana'],
            'saldo' => 0
        ));
        $karhu->tallenna();
        Redirect::to('/karhut/' . $karhu->karhuid, array('viesti' => 'Uusi karhu lisätty'));
    }
    
    
}
