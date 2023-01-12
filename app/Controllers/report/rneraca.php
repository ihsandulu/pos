<?php

namespace App\Controllers\report;


use App\Controllers\baseController;

class rneraca extends baseController
{

    protected $sesi_user;
    public function __construct()
    {
        $sesi_user = new \App\Models\global_m();
        $sesi_user->ceksesi();
    }


    public function index()
    {
        return view('report/rneraca_v');
    } 
    
    public function print()
    {
        return view('report/rneracaprint_v');
    }
}
