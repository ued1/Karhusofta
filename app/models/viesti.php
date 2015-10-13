<?php

class Viesti extends BaseModel {

    public $viestiid, $aika, $viesti, $karhuid, $lahettaja;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function kaikki() {
        $kysely = DB::connection()->prepare("SELECT viestiid, to_char(aika, 'YYYY-MM-DD HH24:MI') as parempiaika, viesti, karhuid FROM Viesti ORDER BY aika DESC");
        $kysely->execute();
        $rivit = $kysely->fetchAll();
        $viestit = array();

        foreach ($rivit as $rivi) {
            $karhu = Karhu::etsi($rivi['karhuid']);
            $viestit[] = new Viesti(array(
                'viestiid' => $rivi['viestiid'],
                'aika' => $rivi['parempiaika'],
                'viesti' => $rivi['viesti'],
                'karhuid' => $rivi['karhuid'],
                'lahettaja' => $karhu->nimi
            ));
        }
        return $viestit;
    }

    
    public function tallenna() {
        $kysely = DB::connection()->prepare('INSERT INTO Viesti (aika, viesti, karhuid) VALUES (now(), :viesti, :karhuid)');
        $kysely->execute(array('karhuid' => $this->karhuid, 'viesti' => $this->viesti));
    }
            
    public function poista() {
        $kysely = DB::connection()->prepare('DELETE FROM Viesti WHERE viestiid = :viestiid');
        $kysely->execute(array('viestiid' => $this->viestiid));
    }

    public static function validoi_viesti($teksti) {
        $virheet = array();
        if ($teksti == null || $teksti == '' || strlen($teksti) > 320) {
            $virheet[] = 'Viestin tulee olla 1-320 merkkiä pitkä!';
        }
        return $virheet;
    }


}
