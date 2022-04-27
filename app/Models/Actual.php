<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actual extends Model
{
    // use HasFactory;
    // protected $table="actuals";
    // protected $fillable= ["provinsi", "year", "actual"]; //database yg diisi
    protected $guarded=[]; //database yg tidak boleh diisi (bisa pilih slah 1 guarded/fillable)
    public function province()
    {
        return $this->belongsTo(Province::class);
    }

}
