<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorites;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class FavoritesController extends Controller
{
    public function index(){
        if(auth('sanctum')->check()){
            $user = auth('sanctum')->user()->id;
            $favorites = Favorites::where('user_id',$user)->get();
            return response()->json([
                'status' => 200,
                'favorites' => $favorites
            ]);
        }else {
            return response()->json([
                'status' => 401,
                'message' => 'Login to view wishlist data'
            ]);
        }
    }
    public function store(Request $request){
        Log::info('User authenticated: ' . auth('sanctum')->check());
        Log::info('Product ID: ' . $request->input('product_id'));
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
                    if(Favorites::where('product_id', $product_id)->where('user_id', $user)->exists()){
                        return response()->json([
                            'status' => 409,
                            'errors' => 'Product already exist'
                        ]);
                    }else{
                        $favorite = new Favorites();
                        $favorite->user_id = $user;
                        $favorite->product_id = $product_id;
                        $favorite->save();
                        return response()->json([
                            'status' => 200,
                            'message' => "Add favorites successfully"
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
