<?php

class EtusivuController extends BaseController {
    
    public static function index() {
        View::make('etusivu.html');
    }
    
    public static function kirjaudu() {
        $parametrit = $_POST;
        $karhu = Karhu::tunnistaudu($parametrit['tunnus'], $parametrit['salasana']);
        if($karhu) {
            $_SESSION['karhu'] = $karhu->karhuid;
            Redirect::to('/', array('viesti' => 'Tervetuloa takaisin ' . $karhu->nimi . '!'));
        } else {
            View::make('etusivu.html', array('virhe' => 'Väärä käyttäjätunnus tai salasana!', 'kayttajatunnus' => $parametrit['tunnus']));
        }
    }
    
}
