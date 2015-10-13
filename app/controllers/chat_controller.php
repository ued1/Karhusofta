<?php

class ChatController extends BaseController {
    
    public static function index() {
        $viestit = Viesti::kaikki();
        View::make('chat.html', array('viestit' => $viestit));
    }
    
    public static function uusi() {
        $parametrit = $_POST;
        $karhu = self::get_user_logged_in();
        $virheet = Viesti::validoi_viesti($parametrit['viesti']);
        if (count($virheet) == 0) {
            $attribuutit = array(
            'viesti' => $parametrit['viesti'],
            'karhuid' => $karhu->karhuid,
            'lahettaja' => $karhu->nimi
            );
            $viesti = new Viesti($attribuutit);
            $viesti->tallenna();
            Redirect::to('/chat');
        } else {
            View::make('chat.html', array('virheet' => $virheet, 'viestit' => Viesti::kaikki()));
        }
    }
    
}
