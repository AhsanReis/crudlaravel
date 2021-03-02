<?php

namespace App\Http\Controllers;

use View;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Skripsi;

class SkripsiController extends Controller
{
    public function index()
    {
        return view('page.skripsi');
    }
    public function insert(Request $request)
    {
        $db = Skripsi::all();
        $db_finger = [];
        $kgram = 8;

        // memproses DB
        foreach($db as $d){ //perulangan setiap value pada database
            $rabinkarp = Self::rabinkarp($request->judulskripsi,$kgram);

            $db_finger[$d->id] = $rabinkarp; //memasukkan array fingerprint dari db kedalam db_finger
        }

        // Return Value
        $rabinkarp = Self::rabinkarp($request->judul,$kgram);
        $dice = Self::diceCoefficient($db_finger,$rabinkarp);
        return $db_finger;
    }
    
    private function rabinkarp($judul,$kgram){
        $judulmod = str_replace(' ','',$judul);
        $judulkecil = strtolower($judulmod);
        $lenght = strlen($judulmod);
        $perstring = []; //menampung hasil pemisahan string
        $ascii = []; //menampung hasil konversi string ke ascii
        $hash = []; //menampung hasil pemisahan string
        // $result =[];


        // Pemisahan String
        for ($i=0; $i<=$lenght; $i++) { 
            $res = substr($judul,$i,$kgram);
            if(strlen($res)==$kgram) {
                array_push($perstring,$res);
            }
            else break;
        }

        // Ascii
        foreach ($perstring as $key => $v) {
            $res = unpack("C*", $v);
            array_push($ascii,$res);
        }

        // Hashing
        foreach ($ascii as $key=>$v){ //perulangan array ascii
            $res = 0;
            $dash = 0;
            $pangkat = $kgram;
            foreach($v as $d){
                $cons = pow(52,$pangkat); // konstanta (pow = pangkat)
                $pangkat--; //pangkat dimulai dari jumlah kgram-1 dan berkurang hingga pangkat 0
                $dash +=($d * $cons);
                // array_push($this->cek,$d);
                $res = $dash;
            }
            array_push($hash,$res);
        }
        // Fingering
        $fingerprint =  array_unique($hash); //eliminasi array yang sama
        return $fingerprint;
        // dice Coeficient similarity
        // $finger_length = count($fingerprint);

        // foreach($db_finger as $key=>$u){
        //     $same_finger = count(array_intersect($fingerprint,$u)); //
        //     $finger_db_length = count($u);
        //     $similarity = ((2*$same_finger) / ($finger_length+$finger_db_length))*100; // rumus dice coeficient similarity
        //     $result[$key] = $similarity;
        // }
        // return $result;
    }

    private function diceCoefficient($db_finger,$rabinkarp)
    {
        $finger_length = count($rabinkarp);
        $result =[];
        foreach($db_finger as $key=>$u){
            $same_finger = count(array_intersect($rabinkarp,$u)); //
            $finger_db_length = count($u);
            $similarity = ((2*$same_finger) / ($finger_length+$finger_db_length))*100; // rumus dice coeficient similarity
            $result[$key] = $similarity;
        }
        return $result;
    }
}