<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    public function index(){
        $brand = Brand::all();
        return response()->json([
            'status'=> 200,
            'brand'=> $brand,
        ]);
    }
    public function all_brand()
    {
        $brand = Brand::where('status', 0)->get();
        return response()->json([
            'status'=> 200,
            'brand'=> $brand,
        ]);
    }
    public function edit($id)
    {
        $brand = Brand::find($id);
            if($brand){
                return response()->json([
                    'status'=> 200,
                    'brand'=> $brand,
                ]);
            }
            else{
                return response()->json([
                    'status'=> 404,
                    'message'=> 'No Brand Id Found',
                ]);
            }
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'meta_title' => 'required',
            'meta_description' => 'required',
            'meta_keyword' => 'required',
            'slug' => 'required',
            'name' => 'required',
        ]);
        if($validator->fails()){
            return response()->json([
                'status'=>422,
                'errors'=>$validator->messages(),
            ]);
        }
        else{
            $brand = Brand::find($id);
            if($brand){
                $brand->meta_title = $request->input('meta_title');
                $brand->meta_description = $request->input('meta_description');
                $brand->meta_keyword = $request->input('meta_keyword');
                $brand->name = $request->input('name');
                $brand->slug = $request->input('slug');
                $brand->description = $request->input('description');
                $brand->status = $request->input('status')==true?'1':'0';

                if ($request->hasFile('image')) {
                    $path = $brand->image;
                    if(File::exists($path)){
                        File::delete($path);
                    }
                    $file = $request->file('image');
                    $extension = $file->getClientOriginalExtension();
                    $filename = time() . '.' . $extension;
                    $file->move('upload/brand/', $filename);
                    $brand->image = 'upload/brand/' . $filename;
                }
                $brand->save();
                return response()->json([
                    'status'=> 200,
                    'message'=> 'Brand Updated Successfully',
                ]);
            }
            else{
                return response()->json([
                    'status'=> 404,
                    'message'=> 'No Brand Id Found',
                ]);
            }
        }
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'meta_title' => 'required',
            'meta_description' => 'required',
            'meta_keyword' => 'required',
            'slug' => 'required',
            'name' => 'required',

        ]);
        if($validator->fails()){
         return response()->json([
             'status'=>400,
             'errors'=>$validator->messages(),
         ]);
        }
        else{
            $brand = new Brand();
            $brand->meta_title = $request->input('meta_title');
            $brand->meta_description = $request->input('meta_description');
            $brand->meta_keyword  = $request->input('meta_keyword');
            $brand->slug = $request->input('slug');
            $brand->name = $request->input('name');
            $brand->description = $request->input('description');
            if ($request->hasFile('image')){
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move('uploads/brand/', $filename);
                $brand->image = 'uploads/brand/' . $filename;
            }
            $brand->status = $request->input('status')==true?'1':'0';
            $brand->save();
            return response()->json([
                'status'=> 200,
                'message'=> 'Brand Created Successfully',
            ]);
        }
    }
    public function destroy($id){
        $brand = Brand::find($id);
        if($brand){
            $brand->delete();
            return response()->json([
                'status'=> 200,
                'message'=> 'Brand Deleted Successfully',
            ]);
        }
        else{
            return response()->json([
                'status'=> 404,
                'message'=> 'No Brand Id Found',
            ]);
        }
    }
}
