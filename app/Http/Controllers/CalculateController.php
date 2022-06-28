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

        $alpha = [0.01,0.02,0.03,0.04,0.05,0.06,0.07,0.08,0.09, 0.1,
        0.11,0.12,0.13,0.14,0.15,0.16,0.17,0.18,0.19,0.2,
        0.21,0.22,0.23,0.24,0.25,0.26,0.27,0.28,0.29,0.3,
        0.31,0.32,0.33,0.34,0.35,0.36,0.37,0.38,0.39,0.4,
        0.41,0.42,0.43,0.44,0.45,0.46,0.47,0.48,0.49,0.5,
        0.51,0.52,0.53,0.54,0.55,0.56,0.57,0.58,0.59,0.6,
        0.61,0.62,0.63,0.64,0.65,0.66,0.67,0.68,0.69,0.7,
        0.71,0.72,0.73,0.74,0.75,0.76,0.77,0.78,0.79,0.8,
        0.81,0.82,0.83,0.84,0.85,0.86,0.87,0.88,0.89,0.9,
        0.91,0.92,0.93,0.94,0.95,0.96,0.97,0.98,0.99];


        
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
           
            $mape = $result->sum('percent_e')/(count($arrF)-1);

            $data[$i]['alpha'] = $a;
            $data[$i]['forecasting'] = round($forecasting, 2);
            $data[$i]['mape'] = round($mape, 4);

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
        foreach ($data as $key => $value) {
            $chartData['mape'][] = $value['mape'];
            $chartData['alpha'][] = $value['alpha'];
            // $chartData['forcasting'][] = round($value['f']);
        }
        $chartData2 = [];
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
                'chartData2' => json_encode($chartData2),
                'provinsi'=> Province::Find($request->provinsi)
            ]);
        } else {
            return view('calculatedes', [
                'results' => $result,
                'alpha' => $data,
                'optimal' => $optimal,
                'chartData' => json_encode($chartData),
                'chartData2' => json_encode($chartData2),
                'provinsi'=> Province::Find($request->provinsi)
            ]);
        }
       

    }

    public function process($forcastings, $a)
    {
        $arrF = [];

        foreach ($forcastings as $key => $forcasting) {

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

    public function processSES($forcastings, $a)
    {
        $arrF = [];

        foreach ($forcastings as $key => $forcasting) {

            if ($key==0) {
                $arrF[$key]['periode'] = $forcasting->year;
                $arrF[$key]['aktual'] = $forcasting->actual;
                $arrF[$key]['f'] = $forcasting->actual;
                $arrF[$key]['e'] = 0;
                $arrF[$key]['abs_e'] = 0;
                $arrF[$key]['percent_e'] = 0;

            } else {
                // get data sebelumnya
                $prevData = $arrF[$key-1];

                $arrF[$key]['periode'] = $forcasting->year;
                $arrF[$key]['aktual'] = $forcasting->actual;
                $arrF[$key]['f'] = $prevData['aktual']* $a + (1 - $a) * $prevData['f'];
                $arrF[$key]['e'] = $forcasting->actual - $arrF[$key]['f'];
                $arrF[$key]['abs_e'] = abs($forcasting->actual - $arrF[$key]['f']);
                $arrF[$key]['percent_e'] = ($arrF[$key]['abs_e'] / $forcasting->actual)*100;
            }
        }

        return $arrF;
    }
}
