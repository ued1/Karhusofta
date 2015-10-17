<?php

class RooliController extends BaseController {

    public static function index() {
        $roolit = Rooli::kaikki();
        View::make('rooli/roolit.html', array('roolit' => $roolit));
    }

    public static function uusi() {
        View::make('rooli/uusi.html');
    }

    public static function muokkaa($rooliid) {
        $rooli = Rooli::etsi($rooliid);
        View::make('rooli/muokkaus.html', array('attribuutit' => $rooli));
    }

    public static function nayta($rooliid) {
        $rooli = Rooli::etsi($rooliid);
        View::make('rooli/rooli.html', array('rooli' => $rooli));
    }

    public static function lisaa() {
        $parametrit = $_POST;
        $attribuutit = array(
            'nimi' => $parametrit['nimi'],
            'kuvaus' => $parametrit['kuvaus'],
            'vaativuuskerroin' => $parametrit['vaativuuskerroin'],
            'maksimimaara' => $parametrit['maksimimaara']
        );
        $rooli = new Rooli($attribuutit);
        $virheet = $rooli->virheet();
        if (count($virheet) == 0) {
            $rooli->tallenna();
            Redirect::to('/roolit/' . $rooli->rooliid, array('viesti' => 'Uusi rooli lisätty'));
        } else {
            View::make('rooli/uusi.html', array('virheet' => $virheet, 'attribuutit' => $attribuutit));
        }
    }
    
    
    public static function paivita($rooliid) {
        $parametrit = $_POST;
        $attribuutit = array(
            'rooliid' => $rooliid,
            'nimi' => $parametrit['nimi'],
            'kuvaus' => $parametrit['kuvaus'],
            'vaativuuskerroin' => $parametrit['vaativuuskerroin'],
            'maksimimaara' => $parametrit['maksimimaara']
        );
        $rooli = new Rooli($attribuutit);
        $virheet = $rooli->virheet();
        if(count($virheet) == 0) {
            $rooli->paivita();
            Redirect::to('/roolit/' . $rooliid, array('viesti' => 'Roolia on muokattu onnistuneesti!'));
        } else {
            View::make('rooli/muokkaus.html', array('virheet' => $virheet, 'attribuutit' => $attribuutit));
        }
    }
    
    public static function poista($rooliid) {
        Rooli::poista($rooliid);
        Redirect::to('/roolit', array('viesti' => 'Rooli poistettu onnistuneesti!'));
    }
    
}
