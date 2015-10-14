<?php

class ChatController extends BaseController {
    
    public static function index() {
        $viestit = Chatviesti::kaikki();
        View::make('chat.html', array('viestit' => $viestit));
    }
    
    public static function uusi() {
        $parametrit = $_POST;
        $karhu = self::get_user_logged_in();
        $virheet = Chatviesti::validoi_viesti($parametrit['viesti']);
        if (count($virheet) == 0) {
            $attribuutit = array(
            'viesti' => $parametrit['viesti'],
            'karhuid' => $karhu->karhuid,
            'lahettaja' => $karhu->nimi
            );
            $chatviesti = new Chatviesti($attribuutit);
            $chatviesti->tallenna();
            Redirect::to('/chat');
        } else {
            View::make('chat.html', array('virheet' => $virheet, 'viestit' => Chatviesti::kaikki()));
        }
    }
    
}
