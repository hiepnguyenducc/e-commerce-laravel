<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Color;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SizeController extends Controller
{
    public function index()
    {
        $size = Size::all();
        return response()->json([
            'status' => 200,
            'size' => $size
        ]);
    }
    public function all_size()
    {
        $size = Size::where('status', 0)->get();
        return response()->json([
            'status' => 200,
            'size' => $size
        ]);
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:size',
            'description' => 'required',
            'code' => 'required|unique:size',
            'length'=>'required',
            'height'=>'required',
            'width'=>'required',
            'weight'=>'required',
        ]) ;
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors()
            ]);
        }else{
            $size = new Size();
           $size ->name = $request->input('name');
           $size->description = $request->input('description');
           $size->code = $request->input('code');
           $size->length = $request->input('length');
           $size->width = $request->input('width');
           $size->height = $request->input('height');
           $size->weight  = $request->input('weight');
           $size->status = $request->input('status')==true?'1':'0';
           $size->save();
           return response()->json([
               'status' => 200,
               'message' => 'Size Created Successfully'
           ]);
        }
    }
    public function edit($id)
    {
        $size = Size::find($id);
        if($size){
            return response()->json([
                'status' => 200,
                'color' => $size
            ]);
        }else{
            return response()->json([
                'status' => 404,
                'message' => 'Color Not Found'
            ]);
        }
    }
    public function update(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:size',
            'description' => 'required',
            'code' => 'required|unique:size',
            'length'=>'required',
            'height'=>'required',
            'width'=>'required',
            'weight'=>'required',
        ]) ;
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors()
            ]);
        }else{
            $size = Color::find($id);
            if($size){
                $size->name = $request->input('name');
                $size->description = $request->input('description');
                $size->code = $request->input('code');
                $size->length = $request->input('length');
                $size->width = $request->input('width');
                $size->height = $request->input('height');
                $size->weight = $request->input('weight');
                $size->status = $request->input('status')==true?'1':'0';
                $size->save();
                return response()->json([
                    'status' => 200,
                    'message' => 'Size Updated Successfully'
                ]);

            }else{
                return response()->json([
                    'status' => 404,
                    'message' => 'Color Not Found'
                ]);
            }
        }
    }
    public function destroy($id){
        $size = Size::find($id);
        if($size){
            $size->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Size Deleted Successfully'
            ]);
        }else{
            return response()->json([
                'status' => 404,
                'message' => 'Size Not Found'
            ]);
        }
    }

}
