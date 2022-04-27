<?php

namespace App\Http\Controllers;

use App\Models\Actual;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActualController extends Controller
{
    public function create(){
        return view('dashboard');
    }
    public function store(Request $request){
        //menggunakan ORM mass asignmnet
        $actual=Actual::create([
            "province_id"=>$request["provinsi"],
            "year"=>$request["tahun"],
            "actual"=>$request["aktual"],
        ]);
        return redirect('/actual')->with('success', 'Data Berhasil Ditambahkan!');
    }
    public function index(){
        $actuals = Actual::all();
        //filter
        if (request()->provinsi) {
           $actuals=Actual::where('province_id',request()->provinsi)->get();
        }
        //
        $provinces = Province::all(); //menggunakan ORM
        return view('dashboard', compact('actuals','provinces'));
    }
    public function edit($id){
        $actual = Actual::find($id);
        $provinces= Province::all();
        return view('edit', compact('actual', 'provinces'));
    }
    public function update($id, Request $request){
        $update=Actual::where('id', $id)->update([
            "province_id"=>$request["provinsi"],
            "year"=>$request["tahun"],
            "actual"=>$request["aktual"],
        ]);
        return redirect('/actual');
        
    }
    public function destroy($id){
        Actual::destroy($id);
        return redirect('/actual');
    }
}
