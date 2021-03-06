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
        // $data['page_title'] = "Skripsi";
        ///////////////////// batas ////////////////////
        $mydata = Skripsi::all();
        return view('page.skripsi', ['datas' => $mydata]);
        // return ;
    }

    public function gateway(Request $request)
    {
        switch ($request->input('action')) {
            case 'check':
                return Self::mainProcess($request);
                break;
            case 'insert':
                return Self::insert($request);
                break;

            case 'index':
                return Self::index();
                break;
        }
    }

    public function mainProcess($request)
    {
        // return 'yay im called';
        $db = Skripsi::all();
        $db_finger = [];
        $kgram = 8;
        $hasilproses = [];
        $data['hasilproses'] = $hasilproses;
        $data['db'] = $db;

        // memproses DB
        foreach ($db as $d) { //perulangan setiap value pada database
            $algoritm = Self::rabinkarp($d->judulskripsi, $kgram);
            $db_finger[$d->nim] = $algoritm; //memasukkan array fingerprint dari db kedalam db_finger
        }

        // Return Value
        $algoritm = Self::rabinkarp($request->judul, $kgram);
        $dice = Self::diceCoefficient($db_finger, $algoritm);

        // return $dice;

        // insert into array db
        $temp_db = $db;
        foreach ($temp_db as $key => $val) {
            $db[$key]->similarity = $dice[$val->nim];
        }

        // maximum limit reached
        $temp_db = $db;
        $max_limit = 50; // in percent
        foreach ($temp_db as $key => $val) {
            if ($val->similarity <= $max_limit) {
                unset($db[$key]);
            } else {
                continue;
            }
        }

        return view('page.skripsi', ['formdata' => $request, 'datas' => $db]);
    }

    public function insert(Request $request)
    {
        $data = new Skripsi();
        $data->nim = $request->nim;
        $data->nama = $request->nama;
        $data->judulskripsi = $request->judul;
        $data->save();

        return redirect()->route('skripsi');
    }

    private function rabinkarp($judul, $kgram)
    {
        $judulmod = strtolower(str_replace(' ', '', $judul));
        $lenght = strlen($judulmod);
        $dbStop = DB::select('select * from tb_stopword');
        $perstring = []; //menampung hasil pemisahan string
        $ascii = []; //menampung hasil konversi string ke ascii
        $hash = [];//menampung hasil pemisahan string

        //PreProcessing
        // Pemisahan String (tokenizing)
        $arrToken = array('.',',','"',"'",'-','/','{','}','+','_','!','@','#','$','%','^','&','*','(',')','?','>','<',']','[','|','`','~',';',':','=','\\',"\n","\r",0,1,2,3,4,5,6,7,8,9,$dbStop);
        $tokeniz = str_replace($arrToken, '',$judulmod);
        for ($i = 0; $i <= $lenght; $i++) {
            $res = substr($judulmod, $i, $kgram);
            if (strlen($res) == $kgram) {
                array_push($perstring, $res);
            } else break;
        }

        // //filtering stopword

        foreach ($dbStop as $row) {
            $stopword[] = trim($row->kata);
        }
        $jml = count($perstring) - 1;
        for($i=0;i<=$jml;i++){
            if (in_array($perstring[$i],$stopword)) {
                unset($)
            }
        }


        // Ascii
        foreach ($perstring as $key => $v) {
            $res = unpack("C*", $v);
            array_push($ascii, $res);
        }

        // Hashing
        foreach ($ascii as $key => $v) { //perulangan array ascii
            $res = 0;
            $dash = 0;
            $pangkat = $kgram;
            foreach ($v as $d) {
                $cons = pow(52, $pangkat); // konstanta (pow = pangkat)
                $pangkat--; //pangkat dimulai dari jumlah kgram-1 dan berkurang hingga pangkat 0
                $dash += ($d * $cons);
                // array_push($this->cek,$d);
                $res = $dash;
            }
            array_push($hash, $res);
        }

        // $result =[];

        // Fingering
        $fingerprint =  array_unique($hash); //eliminasi array yang sama
        return $fingerprint;
    }

    private function diceCoefficient($db_finger, $algoritm)
    {
        $finger_length = count($algoritm);
        $result = [];
        foreach ($db_finger as $key => $u) {
            $same_finger = count(array_intersect($algoritm, $u)); //
            $finger_db_length = count($u);
            $similarity = ((2 * $same_finger) / ($finger_length + $finger_db_length)) * 100; // rumus dice coeficient similarity
            $result[$key] = number_format($similarity,2);
        }
        return $result;
    }
}


