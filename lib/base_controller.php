<?php

  class BaseController{

    public static function get_user_logged_in(){
        if(isset($_SESSION['karhuid'])) {
            $karhuid = $_SESSION['karhuid'];
            $karhu = Karhu::etsi($karhuid);
            return $karhu;
        }
      return null;
    }

    public static function check_logged_in(){
        if(!isset($_SESSION['karhuid'])) {
            Redirect::to('/', array('virhe' => 'Vain kirjautuneet käyttäjät voivat katsoa tietoja!'));
        }
    }
    
    public static function check_admin() {
        $karhu = self::get_user_logged_in();
        if(!$karhu->admin) {
            Redirect::to('/', array('virhe' => 'Valitsemasi toiminto on vain admineille!'));
        }
    }
    
    public static function count_uudet_viestit() {
        if(isset($_SESSION['karhuid'])) {
            $_SESSION['viesteja'] = Viesti::laske_uudet_viestit($_SESSION['karhuid']);
        } else {
            $_SESSION['viesteja'] = null;
        }
    }
  }
