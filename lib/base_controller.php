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

  }
