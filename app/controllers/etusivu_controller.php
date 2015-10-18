<?php

class EtusivuController extends BaseController {

    public static function index() {
        $keikat = array();
        self::count_uudet_viestit();
        if (isset($_SESSION['karhuid'])) {
            $keikat = Karhu::karhun_keikat($_SESSION['karhuid']);
            $uudetviestit = Viesti::uudetviestit($_SESSION['karhuid']);
            $johdettavat_keikat = Karhu::karhun_johdettavat_keikat($_SESSION['karhuid']);
            View::make('etusivu.html', array('keikat' => $keikat, 'uudetviestit' => $uudetviestit, 'lkm' => count($uudetviestit), 'johdettavat_keikat' => $johdettavat_keikat));
        } else {
            View::make('etusivu.html');
        }
    }

    public static function kirjaudu() {
        $parametrit = $_POST;
        $karhu = Karhu::tunnistaudu($parametrit['tunnus'], $parametrit['salasana']);
        if ($karhu) {
            $_SESSION['karhuid'] = $karhu->karhuid;
            $_SESSION['nimi'] = $karhu->nimi;
            Redirect::to('/', array('viesti' => 'Tervetuloa ' . $karhu->nimi . '!'));
        } else {
            View::make('etusivu.html', array('virhe' => 'Väärä käyttäjätunnus tai salasana!', 'kayttajatunnus' => $parametrit['tunnus']));
        }
    }

    public static function poistu() {
        $_SESSION['karhuid'] = null;
        $_SESSION['nimi'] = null;
        Redirect::to('/', array('viesti' => 'Olet kirjautunut ulos!'));
    }

}
