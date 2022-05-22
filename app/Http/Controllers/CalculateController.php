<?php

namespace App\Http\Controllers;

use App\Models\Actual;
use App\Models\Province;
use Illuminate\Http\Request;

class CalculateController extends Controller
{   //select nama provinsi
    public function forecastingSelect()
    {
        $provinces = Province::all(); //menggunakan ORM
        return view('forecasting', compact('provinces'));
    }
    
    //
    public function index(Request $request){
        $forcastings = Actual::where('province_id', $request['provinsi'])->get(); //ambil data berdasar yang dipilih
        // dd($forcastings);
        if(count($forcastings) == 0) {
            return false;
        }//cek jika data kosong

        $alpha = [0.1,0.2,0.3,0.4,0.5,0.6,0.7,0.8,0.9];
        $data = [];
        $arrF = []; //menampung data ketika semua data sudah di looping (array forecasting)

        for ($i=0; $i < count($alpha) ; $i++) { 
            
            $a = $alpha[$i];

            // menjalankan proses SES/DES
            if ($request->metode =='SES') {
                $arrF = $this->processSES($forcastings,$a);
                # code...
            } else {
                $arrF = $this->process($forcastings,$a);
            }

            $result = collect($arrF); //mengubah array ke bentuk collection
            $lastData = last($arrF);
            if ($request->metode =='SES') {
                $forecasting = $lastData['aktual']*$a+(1-$a)*$lastData['f'];
            } else{
                $forecasting = $lastData['a'] + ($lastData['b'] * 1);
            }
           
            $mape = $result->sum('percent_e')/count($arrF);

            $data[$i]['alpha'] = $a;
            $data[$i]['forecasting'] = round($forecasting, 2);
            $data[$i]['mape'] = round($mape, 1);

        }
        // dd($data);
        $arrSort = collect($data)->sortBy('mape')->toArray();
        $optimal = head($arrSort);

        //menghitung keseluruhan forecasting berdasarkan alpha optimal
        if ($request->metode =='SES') {
            $result = $this->processSES($forcastings, $optimal['alpha']);
            # code...
        } else {
            $result = $this->process($forcastings, $optimal['alpha']);
        }

        //chart bar
        $chartData = [];
        foreach ($result as $key => $value) {
            $chartData['periode'][] = $value['periode'];
            $chartData['aktual'][] = $value['aktual'];
            $chartData['forcasting'][] = round($value['f']);
        }
        
        if ($request->metode =='SES') {
            return view('calculateses', [
                'results' => $result,
                'alpha' => $data,
                'optimal' => $optimal,
                'chartData' => json_encode($chartData),
                'provinsi'=> Province::Find($request->provinsi)
            ]);
        } else {
            return view('calculatedes', [
                'results' => $result,
                'alpha' => $data,
                'optimal' => $optimal,
                'chartData' => json_encode($chartData),
                'provinsi'=> Province::Find($request->provinsi)
            ]);
        }
       

    }

    public function process($forcastings, $a)
    {
        $arrF = [];

        foreach ($forcastings as $key => $forcasting) {
            
            // cek apakah data pertama?
            // $isFirst = Actual::find($forcasting->id - 1);

            if ($key==0) {
                $arrF[$key]['periode'] = $forcasting->year;
                $arrF[$key]['aktual'] = $forcasting->actual;
                $arrF[$key]['s1'] = $forcasting->actual;
                $arrF[$key]['s2'] = $forcasting->actual;
                $arrF[$key]['a'] = $forcasting->actual;
                $arrF[$key]['b'] = 0;
                $arrF[$key]['f'] = 0;
                $arrF[$key]['e'] = 0;
                $arrF[$key]['abs_e'] = 0;
                $arrF[$key]['percent_e'] = 0;

            } else {
                // get data sebelumnya
                $prevData = $arrF[$key-1];

                $arrF[$key]['periode'] = $forcasting->year;
                $arrF[$key]['aktual'] = $forcasting->actual;
                $arrF[$key]['s1'] = ($forcasting->actual * $a) + (1 - $a) * $prevData['s1'];
                $arrF[$key]['s2'] = ($arrF[$key]['s1'] * $a) + (1 - $a) * $prevData['s2'];
                $arrF[$key]['a'] = 2 * $arrF[$key]['s1'] - $arrF[$key]['s2'];
                $arrF[$key]['b'] = $a / (1 - $a) * ($arrF[$key]['s1'] - $arrF[$key]['s2']);
                $arrF[$key]['f'] = $prevData['a'] + ($prevData['b'] * 1);
                $arrF[$key]['e'] = $forcasting->actual - $arrF[$key]['f'];
                $arrF[$key]['abs_e'] = abs($forcasting->actual - $arrF[$key]['f']);
                $arrF[$key]['percent_e'] = ($arrF[$key]['abs_e'] / $forcasting->actual)*100;
            }
        }

        return $arrF;
    }

    //perhitungan SES
    // public function indexSES(Request $request){
    //     $forcastings = Actual::where('province_id', $request['provinsi'])->get();
    //     if(count($forcastings) == 0) {
    //         return false;
    //     }

    //     $alpha = [0.1,0.2,0.3,0.4,0.5,0.6,0.7,0.8,0.9];
    //     $data = [];
    //     $arrF = [];

    //     for ($i=0; $i < count($alpha) ; $i++) { 
            
    //         $a = $alpha[$i];

    //         $arrF = $this->processSES($forcastings,$a);

    //         $result = collect($arrF);
    //         $lastData = last($arrF);
    //         $forecasting = $lastData['s1'];
    //         $mape = $result->sum('percent_e')/count($arrF);

    //         $data[$i]['alpha'] = $a;
    //         $data[$i]['forecasting'] = round($forecasting, 2);
    //         $data[$i]['mape'] = round($mape, 1);

    //     }
    //     $arrSort = collect($data)->sortBy('mape')->toArray();
    //     $optimal = head($arrSort);
    //     $result = $this->processSES($forcastings, $optimal['alpha']);

    //     return view('calculateses', [
    //         'results' => $result,
    //         'alpha' => $data,
    //         'optimal' => $optimal,
    //         'provinsi'=> Province::Find($request->provinsi)
    //     ]);

    // }

    public function processSES($forcastings, $a)
    {
        $arrF = [];

        foreach ($forcastings as $key => $forcasting) {
            
            // cek apakah data pertama?
            // $isFirst = Actual::find($forcasting->id - 1);

            if ($key==0) {
                $arrF[$key]['periode'] = $forcasting->year;
                $arrF[$key]['aktual'] = $forcasting->actual;
                $arrF[$key]['f'] = $forcasting->actual;
                // $arrF[$key]['s2'] = $forcasting->actual;
                // $arrF[$key]['a'] = $forcasting->actual;
                // $arrF[$key]['b'] = 0;
                // $arrF[$key]['f'] = 0;
                $arrF[$key]['e'] = 0;
                $arrF[$key]['abs_e'] = 0;
                $arrF[$key]['percent_e'] = 0;

            } else {
                // get data sebelumnya
                $prevData = $arrF[$key-1];

                $arrF[$key]['periode'] = $forcasting->year;
                $arrF[$key]['aktual'] = $forcasting->actual;
                $arrF[$key]['f'] = $prevData['aktual']* $a + (1 - $a) * $prevData['f'];
                // $arrF[$key]['s2'] = ($arrF[$key]['s1'] * $a) + (1 - $a) * $prevData['s2'];
                // $arrF[$key]['a'] = 2 * $arrF[$key]['s1'] - $arrF[$key]['s2'];
                // $arrF[$key]['b'] = $a / (1 - $a) * ($arrF[$key]['s1'] - $arrF[$key]['s2']);
                // $arrF[$key]['f'] = $prevData['a'] + ($prevData['b'] * 1);
                $arrF[$key]['e'] = $forcasting->actual - $arrF[$key]['f'];
                $arrF[$key]['abs_e'] = abs($forcasting->actual - $arrF[$key]['f']);
                $arrF[$key]['percent_e'] = ($arrF[$key]['abs_e'] / $forcasting->actual)*100;
            }
        }

        return $arrF;
    }
}
