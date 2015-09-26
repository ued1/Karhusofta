<?php

class KohdeController extends BaseController {

    public static function index() {
        $kohteet = Kohde::kaikki();
        View::make('kohde/kohteet.html', array('kohteet' => $kohteet));
    }

    public static function uusi() {
        View::make('kohde/uusi.html');
    }

    public static function muokkaa($kohdeid) {
        View::make('kohde/muokkaus.html');
    }

    public static function nayta($kohdeid) {
        $kohde = Kohde::etsi($kohdeid);
        View::make('kohde/kohde.html', array('kohde' => $kohde));
    }

    public static function lisaa() {
        $parametrit = $_POST;
        $attribuutit = array(
            'nimi' => $parametrit['nimi'],
            'osoite' => $parametrit['osoite'],
            'kuvaus' => $parametrit['kuvaus'],
            'arvo' => $parametrit['arvo']
        );
        $kohde = new Kohde($attribuutit);
        $virheet = $kohde->virheet();
        if (count($virheet) == 0) {
            $kohde->tallenna();
            Redirect::to('/kohteet/' . $kohde->kohdeid, array('viesti' => 'Uusi kohde lisätty'));
        } else {
            View::make('kohde/uusi.html', array('virheet' => $virheet, 'attribuutit' => $attribuutit));
        }
    }

}
