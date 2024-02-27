<?php
namespace App\Helper;

class Terbilang
{
  
  public static function bilang($x){
        $_this = new self;
        $x = abs($x);
        $angka = array("", "satu", "dua", "tiga", "empat", "lima",
        "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
        $temp = "";
        if ($x <12) {
            $temp = " ". $angka[$x];
        } else if ($x <20) {
            $temp = $_this->bilang($x - 10). " belas";
        } else if ($x <100) {
            $temp = $_this->bilang($x/10)." puluh". $_this->bilang($x % 10);
        } else if ($x <200) {
            $temp = " seratus" . $_this->bilang($x - 100);
        } else if ($x <1000) {
            $temp = $_this->bilang($x/100) . " ratus" . $_this->bilang($x % 100);
        } else if ($x <2000) {
            $temp = " seribu" . $_this->bilang($x - 1000);
        } else if ($x <1000000) {
            $temp = $_this->bilang($x/1000) . " ribu" . $_this->bilang($x % 1000);
        } else if ($x <1000000000) {
            $temp = $_this->bilang($x/1000000) . " juta" . $_this->bilang($x % 1000000);
        } else if ($x <1000000000000) {
            $temp = $_this->bilang($x/1000000000) . " milyar" . $_this->bilang(fmod($x,1000000000));
        } else if ($x <1000000000000000) {
            $temp = $_this->bilang($x/1000000000000) . " trilyun" . $_this->bilang(fmod($x,1000000000000));
        }     
            return $temp;
    }

}