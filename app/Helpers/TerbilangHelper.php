<?php
namespace App\Helpers;

class TerbilangHelper
{
    private static $baca = [
        '', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh',
        'Sebelas', 'Dua Belas', 'Tiga Belas', 'Empat Belas', 'Lima Belas', 'Enam Belas', 'Tujuh Belas',
        'Delapan Belas', 'Sembilan Belas'
    ];

    private static $satuan = ['', 'Ribu', 'Juta', 'Miliar', 'Triliun'];

    public static function terbilang($angka)
    {
        if ($angka < 0) {
            return 'Minus ' . self::terbilang(abs($angka));
        }

        if ($angka == 0) {
            return 'Nol Rupiah';
        }

        $terbilang = '';
        $tingkat = 0;

        while ($angka > 0) {
            $s = self::konversiTigaDigit($angka % 1000);
            if ($s != '') {
                $terbilang = $s . ' ' . self::$satuan[$tingkat] . ' ' . $terbilang;
            }
            $angka = floor($angka / 1000);
            $tingkat++;
        }

        return trim($terbilang) . ' Rupiah';
    }

    private static function konversiTigaDigit($angka)
    {
        if ($angka == 0) {
            return '';
        }

        $terbilang = '';

        // Handle ratusan
        if ($angka >= 100) {
            if (floor($angka / 100) == 1) {
                $terbilang .= 'Seratus ';
            } else {
                $terbilang .= self::$baca[floor($angka / 100)] . ' Ratus ';
            }
            $angka %= 100;
        }

        // Handle puluhan dan satuan
        if ($angka > 0) {
            if ($angka < 20) {
                $terbilang .= self::$baca[$angka];
            } else {
                $puluhan = floor($angka / 10);
                $satuan = $angka % 10;
                $terbilang .= self::$baca[$puluhan] . ' Puluh ' . self::$baca[$satuan];
            }
        }

        return trim($terbilang);
    }
}
