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
    public function apply_coupon(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Mã giảm giá không hợp lệ',
                'errors' => $validator->errors()->toArray(),
            ], 422);
        }

        $couponCode = $request->get('code');

        $coupon = Coupon::where('code', $couponCode)->first();

        if (!$coupon) {
            return response()->json([
                'message' => 'Mã giảm giá không hợp lệ',
            ], 404);
        }

        if (!$coupon->isActive()) {
            return response()->json([
                'message' => 'Mã giảm giá này hiện không hoạt động',
            ], 400);
        }

        if ($coupon->hasExpirationDate() && now()->gt($coupon->end_date)) {
            return response()->json([
                'message' => 'Mã giảm giá này đã hết hạn',
            ], 400);
        }

        if ($coupon->hasUsageLimit() && $coupon->uses_count >= $coupon->max_uses) {
            return response()->json([
                'message' => 'Mã giảm giá này đã đạt đến giới hạn sử dụng tối đa',
            ], 400);
        }

        // Tính toán chiết khấu dựa trên loại mã giảm giá (phần trăm hoặc số tiền cố định)
        $discount = 0;
        if ($coupon->discount_type === 'percentage') {
            $discount = $request->total_price * ($coupon->discount_value / 100);
        } else if ($coupon->discount_type === 'fixed') {
            $discount = $coupon->discount_value;
        } else {
            // Xử lý các loại chiết khấu không mong đợi (ghi nhật ký hoặc ném ngoại lệ)
            Log::error('Loại chiết khấu mã giảm giá không hợp lệ: ' . $coupon->discount_type);
            return response()->json([
                'message' => 'Lỗi máy chủ nội bộ',
            ], 500); // Internal Server Error
        }

        // Cập nhật tổng giá trị giỏ hàng hoặc đơn hàng của người dùng với giá trị chiết khấu (nếu có)

        // (Phần này phụ thuộc vào logic ứng dụng và cấu trúc dữ liệu cụ thể của bạn)

        // Tăng số lần sử dụng mã giảm giá nếu áp dụng
        if ($coupon->hasUsageLimit()) {
            $coupon->increment('uses_count');
            $coupon->save();
        }
        return response()->json([
            'status'=>200,
            'discount'=>$discount
        ]);

    }

}
