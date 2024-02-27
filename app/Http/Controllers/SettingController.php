<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use App\Setting\Setting;
use Validator;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('setting.index');
    }

    public function general()
    {
        //$companies = Company::all();
        //foreach($companies as $company)
        //{
        //	$company_name = $company->name;
        //	$company_email = $company->email;
        //	$company_phone = $company->phone;
        //	$company_address = $company->address;
        //	$company_latitude = $company->latitude;
        //	$company_longitude = $company->longitude;
        //	$company_header_logo = $company->header_logo;
        //	$company_footer_logo = $company->footer_logo;
        //}

        //return view('admin.general.general', compact('companies','company_name','company_email','company_phone','company_address','company_latitude','company_longitude','company_header_logo','company_footer_logo'));
        return view('general.general');
    }

    public function store(Request $request)
    {
        $rules = Setting::getValidationRules();
        $data = $this->validate($request, $rules);

        $validSettings = array_keys($rules);

        foreach ($data as $key => $val) {
            if( in_array($key, $validSettings) ) {
                Setting::add($key, $val, Setting::getDataType($key));
            }
        }

        return redirect()->back()->with('status', 'Settings has been saved.');
    }
}
