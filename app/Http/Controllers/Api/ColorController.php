<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ColorController extends Controller
{
    public function index(){
        $color = Color::all();
        
        return response()->json([
            'status'=>200,
            'color'=>$color
        ]);
    }
    public function all_color()
    {
        $color = Color::where('status','0')->get();
        return response()->json([
            'status'=>200,
            'color'=>$color
        ]);
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(),[
           'name'=>'required|string',
           'code'=>'required|string',

            'hex_code'=>'required|string',

        ]);
        if($validator->fails()){
            return response()->json([
                'status'=>400,
                'errors'=>$validator->messages()
            ]);
        }else{
            $color = new Color();
            $color->name = $request->input('name');
            $color->code= $request->input('code');

            $color->hex_code = $request->input('hex_code');

            $color->status = $request->input('status')==true ? '1' : '0';
            $color->save();
            return response()->json([
                'status'=>200,
                'message'=>'Color Created successfully'
            ]);
        }
    }
    public function edit($id){
        $color = Color::find($id);
        if ($color){
            return response()->json([
                'status'=>200,
                'color'=>$color
            ]);
        }else{
            return response()->json([
                'status'=>404,
                'message'=>'No Color Id Found'
            ]);
        }
    }
    public function update(Request $request,$id){
        $validator = Validator::make($request->all(),[
            'name'=>'required|string',
            'code'=>'required|string',
            'hex_code'=>'required|string',
        ]);
        if($validator->fails()){
            return response()->json([
                'status'=>422,
                'errors'=>$validator->messages()
            ]);
        }else{
            $color = Color::find($id);
            if($color){
                $color->name = $request->input('name');
                $color->code= $request->input('code');

                $color->hex_code = $request->input('hex_code');
                $color->status = $request->input('status')==true ? '1' : '0';
                $color->save();
                return response()->json([
                    'status'=>200,
                    'message'=>'Color Updated successfully',
                ]);
            }
            else{
                return response()->json([
                    'status'=>404,
                    'message'=>'No Color Id Found'
                ]);
            }
        }

    }
    public function destroy($id){
        $color = Color::find($id);
        if($color){
            $color->delete();
            return response()->json([
                'status'=>200,
                'message'=>'Color Deleted successfully'
            ]);
        }else{
            return response()->json([
                'status'=>404,
                'message'=>'No Color Id Found'
            ]);
        }
    }
}
