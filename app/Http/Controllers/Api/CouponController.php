<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class CouponController extends Controller
{
    public function index(){
        $coupon = Coupon::all();
        return response()->json([
            'status' => 200,
            'coupon' => $coupon
        ]);
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(),[
                'code' => 'required|unique:coupon,code',
                'discount_type' => 'required|in:percentage,fixed',
                'discount_value' => 'required|numeric|min:0',
                'start_date' => 'required|date|after_or_equal:today',
                'end_date' => 'required|date|after:start_date',
                'max_uses' => 'required|integer|min:1',
        ]);
        if($validator->fails()){
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors()
            ]);
        }else{
            $coupon = new Coupon();
            $coupon->code = $request->code;
            $coupon->discount_type = $request->discount_type;
            $coupon->discount_value = $request->discount_value;
            $coupon->start_date = $request->start_date;
            $coupon->end_date = $request->end_date;
            $coupon->max_uses = $request->max_uses;
            $coupon->uses_count = $request->uses_count;
            $coupon->save();
            return response()->json([
                'status' => 200,
                'message' => 'Coupon added successfully'
            ]);
        }

    }
    public function edit($id){
        $coupon = Coupon::find($id);
        if($coupon){
            return response()->json([
                'status'=>200,
                'coupon'=>$coupon,
            ]);
        }else{
            return response()->json([
                'status'=>404,
                'message'=>'No coupon Id Found',
            ]);
        }
    }
    public function destroy($id){
        $coupon = Coupon::find($id);
        if($coupon){
            $coupon->delete();
            return response()->json([
                'status'=>200,
                'message'=>'Coupon Deleted Successfully'
            ]);
        }
        else{
            return response()->json([
                'status'=>404,
                'message'=>'No Coupon ID Found'
            ]);
        }
    }
    public function update(Request $request, $id){
        $validator = Validator::make($request->all(),[
            'code' => 'required|unique:coupon,code',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'max_uses' => 'required|integer|min:1',

        ]);
        if($validator->fails()){
            return response()->json([
                'status'=>422,
                'errors'=>$validator->messages(),
            ]);
        }else{
            $coupon = Coupon::find($id);
            if($coupon){
                $coupon->code = $request->code;
                $coupon->discount_type = $request->discount_type;
                $coupon->discount_value = $request->discount_value;
                $coupon->start_date = $request->start_date;
                $coupon->end_date = $request->end_date;
                $coupon->max_uses = $request->max_uses;
                $coupon->uses_count = $request->uses_count;
                $coupon->save();
                return response()->json([
                    'status'=>200,
                    'message'=>'Coupon Update Successfully'
                ]);
            }
            else
            {
                return response()->json([
                    'status'=>404,
                    'message'=>'No Coupon ID Found'
                ]);
            }

        }
    }
}
