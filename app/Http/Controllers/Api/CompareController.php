<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Compare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
class CompareController extends Controller
{
    public function index(){
        if(auth('sanctum')->check()){
            $user = auth('sanctum')->user()->id;
            $compare = Compare::where('user_id',$user)->get();
            return response()->json([
                'status' => 200,
                'compare' => $compare
            ]);
        }else {
            return response()->json([
                'status' => 401,
                'message' => 'Login to view compare data'
            ]);
        }
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
        ]);
        if($validator->fails()){
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors()
            ]);
        }else{
            if(auth('sanctum')->check()) {
                $user = auth('sanctum')->user()->id;
                $product_id = $request->input('product_id');
                $productCheck = Product::find($product_id);
                if($productCheck){
                    if(Compare::where('product_id', $product_id)->where('user_id', $user)->exists()){
                        return response()->json([
                            'status' => 409,
                            'errors' => 'Product already exist'
                        ]);
                    }else{
                        $compare = new Compare();
                        $compare->user_id = $user;
                        $compare->product_id = $product_id;
                        $compare->save();
                        return response()->json([
                            'status' => 200,
                            'message' => "Add compare successfully"
                        ]);
                    }
                }else{
                    return response()->json([
                        'status' => 404,
                        'message' => 'Product not found'
                    ]);
                }
            }else{
                return response()->json([
                    'status' => 401,
                    'message' => 'Unauthorized'
                ]);
            }
        }
    }
}
