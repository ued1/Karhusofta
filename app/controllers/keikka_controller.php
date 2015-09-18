<?php

class KeikkaController extends BaseController {

    public static function index() {
        $keikat = Keikka::kaikki();
        View::make('keikka/keikat.html', array('keikat' => $keikat));
    }
    
    public static function luo() {
        View::make('keikka/uusi.html');
    }
    
    public static function nayta($keikkaid) {
        $keikka = Keikka::etsi($keikkaid);
        View::make('keikka/keikka.html', array('keikka' => $keikka));
    }

}
