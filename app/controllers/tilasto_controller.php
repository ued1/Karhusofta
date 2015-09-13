<?php

class TilastoController extends BaseController {
    
    public static function index() {
        View::make('tilasto/tilasto.html');
    }

}
