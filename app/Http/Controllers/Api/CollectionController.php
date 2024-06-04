<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class CollectionController extends Controller
{
    public function index(){
        $collection = Collection::all();
        return response()->json([
            'status'=>200,
            'collection'=>$collection
        ]);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required',
            'image' => 'required',
        ]);
        if($validator->fails()){
            return response()->json([
                'status'=>400,
                'errors'=>$validator->messages()
            ]);

        }else{
            $collection = new Collection();

            $collection->slug = $request->input('slug');
            $collection->name = $request->input('name');

            if($request->hasfile('image')){
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move('uploads/collection/', $filename);
                $collection->image = 'uploads/collection/' . $filename;
            }
            $collection->status = $request->input('status')==true?'1':'0';
            $collection->save();
            return response()->json([
                'status'=>200,
                'message'=>'Collection added successfully'
            ]);
        }
    }
    public function edit($id)
    {
        $collection = Collection::find($id);
        if($collection){
            return response()->json([
                'status'=>200,
                'collection'=>$collection
            ]);
        }else{
            return response()->json([
                'status'=>404,
                'message'=>'Collection not found'
            ]);
        }
    }
    public function update(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required',
            'image' => 'required',
        ]);
        if($validator->fails()){
            return response()->json([
                'status'=>400,
                'errors'=>$validator->messages()
            ]);
        }else{
            $collection = Collection::find($id);
            if($collection){
                $collection->name = $request->input('name');
                $collection->slug = $request->input('slug');

                $collection->status = $request->input('status')==true?'1':'0';
                if($request->hasfile('image')){
                    $path = $collection->name;
                    if(File::exists($path)){
                        File::delete($path);
                    }
                    $file = $request->file('image');
                    $extension = $file->getClientOriginalExtension();
                    $filename = time() . '.' . $extension;
                    $file->move('uploads/collection/', $filename);
                    $collection->image = 'uploads/collection/' . $filename;

                }
                $collection->save();
                return response()->json([
                    'status'=>200,
                    'message'=>'Collection updated successfully'
                ]);
            }else{
                return response()->json([
                    'status'=>404,
                    'message'=>'Collection not found'
                ]);
            }
        }
    }
    public function destroy($id){
        $collection = Collection::find($id);
        if($collection){
            $collection->delete();
            return response()->json([
                'status'=>200,
                'message'=>'Collection deleted successfully'
            ]);
        }else{
            return response()->json([
                'status'=>404,
                'message'=>'Collection not found'
            ]);
        }
    }
}

