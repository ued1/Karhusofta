<?php

class TilastoController extends BaseController {
    
    public static function index() {
        $tilasto = Tilasto::luo_tilasto();
        View::make('tilasto/tilasto.html', array('tilasto' => $tilasto));
    }
    
}
