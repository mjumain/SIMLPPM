<?php


class Uang
{
  
  public static function format_uang($input)
  {
      $jumlah_desimal ="0";
      $pemisah_desimal =",";
      $pemisah_ribuan =".";
      $uang="Rp ".number_format($input, $jumlah_desimal, $pemisah_desimal, $pemisah_ribuan).",-";
      return $uang;
  }
   public static function uang_singkat($input)
  {
    if (strlen($input) <= 6) {
      $uang=self::format_uang($input);
    }else{
      $jumlah_desimal ="0";
      $pemisah_desimal =",";
      $pemisah_ribuan =".";
      $uang=number_format($input, $jumlah_desimal, $pemisah_desimal, $pemisah_ribuan);
      if (strlen($uang) == 10) {
        $uang=substr($uang, 0, 4).' Juta';
      }else{
        $uang=substr($uang, 0, 5).' Juta';
      }
    }
    return $uang;
  }
  public static function terbilang($x, $style=3) {
    if($x<0) {
        $hasil = "minus ". trim(Uang::kekata(round($x,0)));
      } else {
          $hasil = trim(Uang::kekata(round($x,0)));
      }     
      switch ($style) {
          case 1:
              $hasil = strtoupper($hasil);
              break;
          case 2:
              $hasil = strtolower($hasil);
              break;
          case 3:
              $hasil = ucwords($hasil);
              break;
          default:
              $hasil = ucfirst($hasil);
              break;
      }     
      return "(".$hasil." Rupiah)";
  }
  public static function terbilang_bersih($x, $style=4) {
    if($x<0) {
        $hasil = "minus ". trim(Uang::kekata(round($x,0)));
      } else {
          $hasil = trim(Uang::kekata(round($x,0)));
      }     
      switch ($style) {
          case 1:
              $hasil = strtoupper($hasil);
              break;
          case 2:
              $hasil = strtolower($hasil);
              break;
          case 3:
              $hasil = ucwords($hasil);
              break;
          case 4:
              $hasil = ucfirst($hasil);
              break;
          default:
              $hasil = ucfirst($hasil);
              break;
      }     
      return $hasil.' rupiah';
  }
  public static function kekata($x) {
      $x = abs($x);
      $angka = array("", "satu", "dua", "tiga", "empat", "lima",
      "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
      $temp = "";
      if ($x <12) {
          $temp = " ". $angka[$x];
      } else if ($x <20) {
          $temp = Uang::kekata($x - 10). " belas";
      } else if ($x <100) {
          $temp = Uang::kekata($x/10)." puluh". Uang::kekata($x % 10);
      } else if ($x <200) {
          $temp = " seratus" . Uang::kekata($x - 100);
      } else if ($x <1000) {
          $temp = Uang::kekata($x/100) . " ratus" . Uang::kekata($x % 100);
      } else if ($x <2000) {
          $temp = " seribu" . Uang::kekata($x - 1000);
      } else if ($x <1000000) {
          $temp = Uang::kekata($x/1000) . " ribu" . Uang::kekata($x % 1000);
      } else if ($x <1000000000) {
          $temp = Uang::kekata($x/1000000) . " juta" . Uang::kekata($x % 1000000);
      } else if ($x <1000000000000) {
          $temp = Uang::kekata($x/1000000000) . " milyar" . Uang::kekata(fmod($x,1000000000));
      } else if ($x <1000000000000000) {
          $temp = Uang::kekata($x/1000000000000) . " trilyun" . Uang::kekata(fmod($x,1000000000000));
      }     
      return $temp;
  }
}