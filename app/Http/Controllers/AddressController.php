<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;

class AddressController extends Controller
{
    public function country($id)
    {
        if($id==0) {
            $provinsi = Provinsi::all();
        } else {
            $provinsi = Provinsi::where('country_id',$id)->get();
        }
        //dd($id);
        return $provinsi;
        //return response()->json(array('result' => 'success'));
    }

    public function provinsi($id)
    {
        if($id==0) {
            $kabupaten = Kabupaten::all();          
        } else {
            $kabupaten = Kabupaten::where('provinsi_id',$id)->get();            
        }
        return $kabupaten;
        //return response()->json(array('result' => 'success'));
    }

    public function kabupaten($id)
    {
        if($id==0) {
            $kecamatans = Kecamatan::all();         
        } else {
            $kecamatans = Kecamatan::where('kabupaten_id',$id)->get();          
        }
        return $kecamatans;
    }

    public function kecamatan($id)
    {
        if($id==0) {
            $kelurahans = Kelurahan::all();           
        } else {
            $kelurahans = Kelurahan::where('kecamatan_id',$id)->get();            
        }
        return $kelurahans;
    }
	
	public function kelurahan($id)
    {
        if($id==0) {
            $kotas = Kelurahan::all();           
        } else {
            $kotas = Kelurahan::where('kecamatan_id',$id)->get();            
        }
        return $kotas;
    }
}
