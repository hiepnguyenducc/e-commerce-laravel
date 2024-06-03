<?php

namespace App\Http\Controllers;

use App\Models\Compare;
use Illuminate\Http\Request;

class CompareController extends Controller
{
    public function index(){
        $compare = Compare::all();
        return response()->json([
            'status'=>200,
            'compare'=>$compare
        ]);
    }
}
