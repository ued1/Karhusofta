<?php

class BaseModel {

    protected $validators;

    public function __construct($attributes = null) {
        foreach ($attributes as $attribute => $value) {
            if (property_exists($this, $attribute)) {
                $this->{$attribute} = $value;
            }
        }
    }

    public function virheet() {
        $virheet = array();
        foreach ($this->validators as $validator) {
            $virheet = array_merge($virheet, $this->{$validator}());
        }
        return $virheet;
    }

    public function merkkijono_tarpeeksi_pitka($string, $length) {
        if ($string == null || strlen($string) < $length) {
            return FALSE;
        }
        return TRUE;
    }

    public function merkkijono_tarpeeksi_lyhyt($string, $length) {
        if (strlen($string) > $length) {
            return FALSE;
        }
        return TRUE;
    }

}
