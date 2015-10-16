<?php

class KeikkaController extends BaseController {

    public static function index($viesti, $virhe) {
        $uudet_keikat = Keikka::keikat_ilmoittautuminen();
        $vanhat_keikat = Keikka::keikat_paattyneet();
        self::count_uudet_viestit();
        foreach($uudet_keikat as $keikka) {
            $keikka->lisaa_ilmoittautumistieto();
            $keikka->lisaa_oma_ilmoittautumistieto($_SESSION['karhuid']);
        }
        View::make('keikka/keikat.html', array('keikat' => $uudet_keikat, 'viesti' => $viesti, 'virhe' => $virhe, 'vanhat_keikat' => $vanhat_keikat));
    }
    
    public static function uusi($kohdeid) {
        $kohteet = Kohde::kaikki();
        $karhut = Karhu::kaikki();
        $roolit = Rooli::kaikki();
        if($kohdeid) {
            $valittu_kohde = Kohde::etsi($kohdeid);
            View::make('keikka/uusi.html', array('kohteet' => $kohteet, 'karhut' => $karhut, 'valittu_kohde' => $valittu_kohde, 'roolit' => $roolit));
        } else {
            View::make('keikka/uusi.html', array('kohteet' => $kohteet, 'karhut' => $karhut, 'roolit' => $roolit));
        }
    }

    public static function nayta($keikkaid) {
        $keikka = Keikka::etsi($keikkaid);
        View::make('keikka/keikka.html', array('keikka' => $keikka));
    }
    
    public static function aloita($keikkaid) {
        $keikka = Keikka::etsi($keikkaid);
        if(!$keikka) {
            Redirect::to('/keikat', array('virhe' => 'Et voi aloittaa keikkaa, mitä ei ole olemassa.'));
        } elseif($keikka->paikka) {
            Redirect::to('/keikat', array('virhe' => 'Keikkaa ei voi aloittaa uudestaan.'));
        }
        if($keikka->aloita($_SESSION['karhuid'])) {
            Redirect::to('/keikat', array('viesti' => 'Keikka aloitettu! Muista kirjata keikan tulos keikan jälkeen.'));
        } else {
            Redirect::to('/keikat', array('virhe' => 'Et voi aloittaa valitsemaasi keikkaa koska et ole sen ryhmänjohtaja.'));
        }
    }

    public static function lisaa() {
        $parametrit = $_POST;
        $valittu_kohdeid = $parametrit['kohde'];
        
        $attribuutit = array(
            'nimi' => $parametrit['nimi'],
            'osallistujamaara' => $parametrit['osallistujamaara'],
            'kohdeid' => $valittu_kohdeid,
            'karhuid' => $_SESSION['karhuid']
        );
                        
        $keikka = new Keikka($attribuutit);
        
        $virheet = $keikka->virheet();
        if (count($virheet) == 0) {
            $keikka->tallenna();
            Redirect::to('/keikat/' . $keikka->keikkaid, array('viesti' => 'Uusi keikka lisätty'));
        } else {
            $kohteet = Kohde::kaikki();
            $valittu_kohde = Kohde::etsi($valittu_kohdeid);
            View::make('keikka/uusi.html', array('kohteet' => $kohteet, 'virheet' => $virheet, 'attribuutit' => $attribuutit, 'valittu_kohde' => $valittu_kohde));
        }
    }
    
    public static function ilmoittaudu($keikkaid) {
        $keikka = Keikka::etsi($keikkaid);
        $karhuid = $_SESSION['karhuid'];
        if(Karhu::onko_karhu_keikalla($karhuid, $keikkaid)) {
            Redirect::to('/keikat', array('viesti' => null, 'virhe' => 'Et voi ilmoittautua uudestaan samalle keikalle!'));
        } elseif($keikka->karhuid == $karhuid) {
            Redirect::to('/keikat', array('viesti' => null, 'virhe' => 'Olet jo ryhmänjohtajana keikalla!'));
        } elseif($keikka->onko_keikalla_tilaa()) {
            $karhun_roolit = Rooli::karhun_taidot($_SESSION['karhuid']);
            if($karhun_roolit == null) {
                self::lisaa_ilmoittautuminen($keikkaid);
            } else {
                View::make('keikka/ilmoittautuminen.html', array('roolit' => $karhun_roolit, 'keikka' => $keikka));
            }
        } else {
            Redirect::to('/keikat', array('viesti' => null, 'virhe' => 'Keikalle ei mahdu enempää, se on täynnä!'));
        }   
    }
    
    public static function lisaa_ilmoittautuminen($keikkaid) {
        if($_POST) {
            $parametrit = $_POST;
            $rooliid = $parametrit['rooliid'];
            $viesti = "Ilmoittautuminen lisätty!";
        } else {
            $rooliid = null;
            $viesti = "Sinulla ei ole erityistaitoja, mutta pääset keikalle jokapaikanhöylääjäksi. Ilmoittautuminen lisätty!";
        }
        Keikka::ilmoittaudu($keikkaid, $_SESSION['karhuid'], $rooliid);
        Redirect::to('/keikat', array('viesti' => $viesti, 'virhe' => null));
    }
    
    public static function peru_ilmoittautuminen($keikkaid) {
        $keikka = Keikka::etsi($keikkaid);
        $karhuid = $_SESSION['karhuid'];
        if(Karhu::onko_karhu_keikalla($karhuid, $keikkaid)) {
            Keikka::peru_osallistuminen($keikkaid, $karhuid);
            Redirect::to('/keikat', array('viesti' => 'Ilmoittautuminen peruttu!', 'virhe' => null));
        } else {
            Redirect::to('/keikat', array('viesti' => null, 'virhe' => 'Et voi perua ilmoittautumista koska et ole kyseisellä keikalla!'));
        }
    }
    
    public static function kirjaa_tulos($keikkaid) {
        $keikka = Keikka::etsi($keikkaid);
        if(!$keikka) {
            Redirect::to('/keikat', array('virhe' => 'Keikkaa ei ole olemassa.'));
        } elseif($keikka->suoritettu) {
            Redirect::to('/keikat', array('virhe' => 'Tulosta ei voi kirjata uudestaan'));
        } elseif($keikka->karhuid != $_SESSION['karhuid']) {
            Redirect::to('/keikat', array('virhe' => 'Et voi kirjata tulosta, koska et ole keikan ryhmänjohtaja'));
        }
        View::make('keikka/kirjaus.html', array('keikka' => $keikka));
    }
    
    public static function tallenna_tulos($keikkaid) {
        $parametrit = $_POST;
        $keikka = Keikka::etsi($keikkaid);
        $keikka->saalis = $parametrit['saalis'];
        $keikka->kommentti = $parametrit['kommentti'];
        $virheet = $keikka->validoi_tulos();
        if(count($virheet) == 0) {
            $keikka->kirjaa_tulos($_SESSION['karhuid']);
            Redirect::to('/keikat', array('viesti' => 'Keikan tulos kirjattu!'));
        } else {
            View::make('keikka/kirjaus.html', array('virheet' => $virheet, 'keikka' => $keikka));
        }
    }

}
