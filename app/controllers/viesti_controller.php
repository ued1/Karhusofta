<?php

class ViestiController extends BaseController {
    
    public static function index() {
        self::count_uudet_viestit();
        $saapuneet = Viesti::saapuneet($_SESSION['karhuid']);
        $lahetetyt = Viesti::lahetetyt($_SESSION['karhuid']);
        View::make('viesti/viestit.html', array('saapuneet' => $saapuneet, 'lahetetyt' => $lahetetyt));
    }
    
    public static function uusi() {
        $karhut = Karhu::kaikki();
        View::make('viesti/uusi.html', array('karhut' => $karhut));
    }
    
    public static function laheta() {
        $parametrit = $_POST;
        $attribuutit = array(
            'otsikko' => $parametrit['otsikko'],
            'saajaid' => $parametrit['saajaid'],
            'viesti' => $parametrit['viesti'],
            'saajanimi' => Karhu::etsi($parametrit['saajaid'])->nimi
        );
        $viesti = new Viesti($attribuutit);
        $virheet = $viesti->virheet();
        if (count($virheet) == 0) {
            $viesti->tallenna($_SESSION['karhuid']);
            Redirect::to('/viestit', array('viesti' => 'Viesti lähetetty!'));
        } else {
            $karhut = Karhu::kaikki();
            View::make('viesti/uusi.html', array('virheet' => $virheet, 'karhut' => $karhut, 'attribuutit' => $attribuutit));
        }
    }
    
    public static function vastaa($viestiid) {
        $viesti = Viesti::etsi($viestiid);
        $attribuutit = array(
            'otsikko' => "Re: " . $viesti->otsikko,
            'saajaid' => $viesti->lahettajaid,
            'saajanimi' => $viesti->lahettajanimi
        );
        $karhut = Karhu::kaikki();
        View::make('viesti/uusi.html', array('karhut' => $karhut, 'attribuutit' => $attribuutit));
    }
    
    public static function nayta($viestiid) {
        $uusiviesti = Viesti::etsi($viestiid);
        if($uusiviesti != null && $uusiviesti->onko_lukuoikeus($_SESSION['karhuid'])) {
            if($uusiviesti->lukemisaika == null) {
                $uusiviesti->aseta_luetuksi();
                self::count_uudet_viestit();
            }
            View::make('viesti/viesti.html', array('uusiviesti' => $uusiviesti));
        } else {
            Redirect::to('/viestit', array('virhe' => 'Sinulla ei ole lukuoikeuksia valitsemaasi viestiin!'));
        }
    }
    
    public static function poista($viestiid) {
        $viesti = new Viesti(array('viestiid' => $viestiid));
        if($viesti->poista($_SESSION['karhuid'])) {
            Redirect::to('/viestit', array('viesti' => 'Viesti poistettu!'));
        } else {
            Redirect::to('/viestit', array('virhe' => 'Viestiä ei voi poistaa!'));
        }
    }
    
    
    
}
