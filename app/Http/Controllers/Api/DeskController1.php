<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Desk;
use Illuminate\Http\Request;

class DeskController1 extends Controller
{
    public function index()
    {
        return Desk::all();
    } 
}
