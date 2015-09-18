<?php

class TilastoController extends BaseController {
    
    public static function index() {
        $karhuja = Karhu::lukumaara();
        View::make('tilasto/tilasto.html', array('karhut' => $karhuja));
    }

}
