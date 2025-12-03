<?php
class mobil{
    var $merk="toyota";
    var $warna="hitam";
    var $harga="200.000.000";

    function gantiwarna($warna_baru){
        $this->warna=$warna_baru;
    }
    function tampilwarna(){
        echo "warna mobil ".$this->warna;
    }
}

?>