<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Orders;
use Illuminate\Http\Request;

class OrderController extends Controller
{
   public function index(){
       $order= Orders::all();
       return response()->json([
           'status'=>200,
           'order'=>$order
       ]);
   }
}
