<?php

    class KohdeController extends BaseController {
        
        public static function index() {
            View::make('kohde/kohteet.html');
        }
        
        public static function luo() {
            View::make('kohde/uusi.html');
        }
        
        public static function muokkaa($id) {
            View::make('kohde/muokkaus.html');
        }
        
        public static function nayta($id) {
            View::make('kohde/kohde.html');
        }
        
    }
