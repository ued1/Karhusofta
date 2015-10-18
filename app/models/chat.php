<?php

class Chatviesti extends BaseModel {

    public $chatviestiid, $aika, $viesti, $karhuid, $lahettaja;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function kaikki() {
        $kysely = DB::connection()->prepare("SELECT chatviestiid, to_char(aika, 'YYYY-MM-DD HH24:MI') as parempiaika, viesti, karhuid FROM Chat ORDER BY aika DESC LIMIT 12");
        $kysely->execute();
        $rivit = $kysely->fetchAll();
        $viestit = array();

        foreach ($rivit as $rivi) {
            $karhu = Karhu::etsi($rivi['karhuid']);
            $viestit[] = new Chatviesti(array(
                'chatviestiid' => $rivi['chatviestiid'],
                'aika' => $rivi['parempiaika'],
                'viesti' => $rivi['viesti'],
                'karhuid' => $rivi['karhuid'],
                'lahettaja' => $karhu->nimi
            ));
        }
        return $viestit;
    }

    public function tallenna() {
        $kysely = DB::connection()->prepare('INSERT INTO Chat (aika, viesti, karhuid) VALUES (now(), :viesti, :karhuid)');
        $kysely->execute(array('karhuid' => $this->karhuid, 'viesti' => $this->viesti));
    }

    public function poista() {
        $kysely = DB::connection()->prepare('DELETE FROM Chat WHERE chatviestiid = :chatviestiid');
        $kysely->execute(array('chatviestiid' => $this->chatviestiid));
    }

    public static function poista_kaikki() {
        $kysely = DB::connection()->prepare('DELETE FROM Chat');
        $kysely->execute();
    }

    public static function validoi_viesti($teksti) {
        $virheet = array();
        if ($teksti == null || $teksti == '' || strlen($teksti) > 320) {
            $virheet[] = 'Viestin tulee olla 1-320 merkkiä pitkä!';
        }
        return $virheet;
    }

}
