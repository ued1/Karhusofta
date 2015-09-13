<?php

class KarhuController extends BaseController {

    public static function index() {
        View::make('karhu/karhut.html');
    }

    public static function luo() {
        View::make('karhu/uusi.html');
    }
    
    public static function muokkaa($id) {
        View::make('karhu/muokkaus.html');
    }
    
    public static function nayta($id) {
        View::make('karhu/karhu.html');
    }
    
}
