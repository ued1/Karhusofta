<?php

    class KohdeController extends BaseController {
        
        public static function index() {
            $kohteet = Kohde::kaikki();
            View::make('kohde/kohteet.html', array('kohteet' => $kohteet));
        }
        
        public static function luo() {
            View::make('kohde/uusi.html');
        }
        
        public static function muokkaa($kohdeid) {
            View::make('kohde/muokkaus.html');
        }
        
        public static function nayta($kohdeid) {
            $kohde = Kohde::etsi($kohdeid);
            View::make('kohde/kohde.html', array('kohde' => $kohde));
        }
        
    }
