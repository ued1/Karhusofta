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
        $karhu = Karhu::etsi($karhuid);
        View::make('karhu/muokkaus.html', array('attribuutit' => $karhu));
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
    
    public static function paivita($karhuid) {
        $parametrit = $_POST;
        $attribuutit = array(
            'karhuid' => $karhuid,
            'nimi' => $parametrit['nimi'],
            'salasana' => $parametrit['salasana']
        );
        $karhu = new Karhu($attribuutit);
        $virheet = $karhu->virheet();
        if(count($virheet) == 0) {
            $karhu->paivita();
            Redirect::to('/karhut/' . $karhuid, array('viesti' => 'Karhua on muokattu onnistuneesti!'));
        } else {
            View::make('karhu/muokkaus.html', array('virheet' => $virheet, 'attribuutit' => $attribuutit));
        }
    }
    
    public static function poista($karhuid) {
        $karhu = new Karhu(array('karhuid' => $karhuid));
        if($karhu->voiko_poistaa()) {
            $karhu->poista();
            Redirect::to('/karhut', array('viesti' => 'Karhu poistettu onnistuneesti!'));
        } else {
            $alkuperainen_karhu = Karhu::etsi($karhuid);
            View::make('karhu/karhu.html', array('karhu' => $alkuperainen_karhu, 'virhe' => 'Karhua ei voi poistaa, koska se on ryhmänjohtajana meneillään olevassa keikassa.'));
        }
    }
    
    
    
}
