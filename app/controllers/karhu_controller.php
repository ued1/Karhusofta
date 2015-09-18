<?php

class KarhuController extends BaseController {

    public static function index() {
        $karhut = Karhu::kaikki();
        View::make('karhu/karhut.html', array('karhut' => $karhut));
    }

    public static function luo() {
        View::make('karhu/uusi.html');
    }
    
    public static function muokkaa($karhuid) {
        View::make('karhu/muokkaus.html');
    }
    
    public static function nayta($karhuid) {
        $karhu = Karhu::etsi($karhuid);
        View::make('karhu/karhu.html', array('karhu' => $karhu));
    }
    
}
