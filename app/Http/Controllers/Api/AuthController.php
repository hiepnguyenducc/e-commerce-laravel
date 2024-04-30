<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Response; // Sửa đổi import này
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:191',
            'email' => 'required|email|max:191|unique:users,email',
            'password' => 'required|min:8'
        ], [
            'name.required' => 'Tên là trường bắt buộc.',
            'name.max' => 'Tên không được vượt quá :max ký tự.',
            'email.required' => 'Email là trường bắt buộc.',
            'email.email' => 'Email phải là địa chỉ email hợp lệ.',
            'email.max' => 'Email không được vượt quá :max ký tự.',
            'email.unique' => 'Email đã được sử dụng.',
            'password.required' => 'Mật khẩu là trường bắt buộc.',
            'password.min' => 'Mật khẩu phải có ít nhất :min ký tự.'
        ]);


        if ($validator->fails()) {
            return Response::json([
                'validation_errors' => $validator->messages(),
            ], 422);
        } else {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $token = $user->createToken($user->email . '_Token')->plainTextToken;

            return Response::json([
                'status' => 200,
                'username' => $user->name,
                'token' => $token,
                'message' => 'Register Successfully',
            ]);
        }
    }
    public function login(Request $request){
        $validator = Validator::make($request->all(),[
            'email'=>'required|email',
            'password'=>'required',
        ]);
        if ($validator->fails()){
            return response()->json([
                'validation_errors'=>$validator->messages(),
            ]);
        }
        else{
            $user = User::where('email',$request->email)->first();
            if (!$user || !Hash::check($request->password, $user->password)){
                return response()->json([
                    'status'=>'401',
                    'message'=>'Invalid Credentials',
                ]);
            }
            else{
                if ($user->role_as == 1){
                    $role = 'admin';
                    $token =  $user->createToken($user->email.'_AdminToken',['server:admin'])->plainTextToken;
                }else{
                    $role = '';
                $token = $user->createToken($user->email.'_Token',[''])->plainTextToken;

                }
                return Response::json([
                    'status' => 200,
                    'username' => $user->name,
                    'token' => $token,
                    'message' => 'Logged In Successfully',
                    'role'=>$role,
                ]);
            }
        }
    }
    public function logout(){
        auth()->user()->tokens()->delete();
        return response()->json([
            'status'=>200,
            'message'=>'Logout Out Successfully',

        ]);
    }
}
