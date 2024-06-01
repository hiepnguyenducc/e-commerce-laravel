<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add_to_cart(Request $request)
    {
        if(auth('sanctum')->check()){
            $user_id = auth('sanctum')->user()->id;
            $product_id = $request->input('product_id');
            $product_qty = $request->input('product_qty');
            $color_id = $request->input('color_id');
            $size_id = $request->input('size_id');

            // Kiểm tra sản phẩm tồn tại
            $productCheck = Product::find($product_id);

            if($productCheck){
                // Kiểm tra nếu số lượng sản phẩm trong kho đủ để thêm vào giỏ hàng
                if($productCheck->quantity >= $product_qty) {
                    // Kiểm tra nếu sản phẩm đã tồn tại trong giỏ hàng
                    if(Cart::where('user_id', $user_id)->where('product_id', $product_id)->exists()){
                        return response()->json([
                            'status' => 409,
                            'message' => $productCheck->name. "Already added to cart!",
                        ]);
                    } else {
                        $cartItem = new Cart();
                        $cartItem->user_id = $user_id;
                        $cartItem->product_id = $product_id;
                        $cartItem->product_qty = $product_qty;
                        $cartItem->color_id = $color_id;
                        $cartItem->size_id = $size_id;
                        $cartItem->save();

                        // Giảm số lượng sản phẩm trong kho
                        $productCheck->quantity -= $product_qty;
                        $productCheck->save();

                        return response()->json([
                            'status' => 201,
                            'message' => "Added to cart!",
                        ]);
                    }
                } else {
                    return response()->json([
                        'status' => 400,
                        'message' => "Not enough quantity in stock",
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => "Product Not Found",
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Login to Add to cart'
            ]);
        }
    }
    public function index()
    {
        if(auth('sanctum')->check()){
            $user = auth('sanctum')->user()->id;
            $cart = Cart::where('user_id', $user)->get();
            return response()->json([
                'status' => 200,
                'cart' => $cart
            ]);
        }else{
            return response()->json([
                'status' => 401,
                'message' => 'Login to View cart data'
            ]);
        }

    }
    public function destroy($id)
    {
        if(auth('sanctum')->check()){
            $cart = Cart::find($id);
            if($cart){
                $cart->delete();
                return response()->json([
                    'status' => 200,
                    'message' => 'Cart Item Removed'
                ]);
            }else{
                return response()->json([
                    'status' => 404,
                    'message' => 'Cart Item Not Found'
                ]);
            }
        }
    }

}
