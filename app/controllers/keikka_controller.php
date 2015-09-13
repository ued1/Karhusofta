<?php

class KeikkaController extends BaseController {

    public static function index() {
        View::make('keikka/keikat.html');
    }
    
    public static function luo() {
        View::make('keikka/uusi.html');
    }
    
    public static function nayta($id) {
        View::make('keikka/keikka.html');
    }

}
