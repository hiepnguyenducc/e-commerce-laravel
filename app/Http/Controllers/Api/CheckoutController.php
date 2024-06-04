<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Orders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CheckoutController extends Controller
{
    public function placeorder(Request $request){
        if(auth('sanctum')->check()){
            $validator = Validator::make($request->all(),[
                'name'=>'required|max:191',
                'email'=>'required|max:191',
                'city'=>'required|max:191',
                'telephone'=>'required|max:191',
                'last_name'=>'required|max:191',
                'full_address'=>'required|max:191',
                'postal_code'=>'required|max:191',

            ]);
            if($validator->fails()){
                return response()->json([
                    'status'=>422,
                    'errors'=>$validator->messages(),
                ]);
            }else{
                $user_id = auth('sanctum')->user()->id;
                $order = new Orders();
                $order->user_id = $user_id;
                $order->name = $request->name;
                $order->last_name = $request->last_name;
                $order->email = $request->email;
                $order->city = $request->city;
                $order->full_address = $request->full_address;
                $order->telephone = $request->telephone;
                $order->postal_code = $request->postal_code;
                $order->total_amount = $request->total_amount;
                $order->payment_method = "COD";
                $order->tracking_no = "hiepluaga".rand(1111,9999);
               $order->save();

               $cart = Cart::where('user_id',$user_id)->get();
               $oderitems = [];
               foreach($cart as $item){
                $oderitems[]=[
                    'product_id'=>$item->product_id,
                    'qty'=>$item->product_qty,
                    'price'=>$item->product->original_price
                ];
                $item->product->update([
                    'quantity'=>$item->product->quantity- $item->product_qty
                ]);
               }
                $order->orderitems()->createMany($oderitems);

                Cart::destroy($cart);
                return response()->json([
                    'status'=>200,
                    'message'=>"Order Placed Successfully",
                ]);

            }
        }else{
            return response()->json([
                'status'=>401,
                'message'=>'Login to continue'
            ]);
        }
    }
}
