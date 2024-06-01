<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use http\Env\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){
        $category = Category::all();
        return response()->json([
            'status'=> 200,
            'category'=> $category,
        ]);
    }
    public function all_category(){
        $category = Category::where('status','0')->get();
        return response()->json([
            'status'=>200,
            'category'=>$category,
        ]);
    }
    public function edit($id){
        $category = Category::find($id);
        if($category){
            return response()->json([
                'status'=>200,
                'category'=>$category,
            ]);
        }else{
            return response()->json([
               'status'=>404,
               'message'=>'No category Id Found',
            ]);
        }
    }
    public function update(Request $request, $id){
        $validator = Validator::make($request->all(),[
            'meta_title'=>'required|max:191',
            'slug'=>'required|max:191',
            'name'=>'required|max:191',

        ]);
        if($validator->fails()){
            return response()->json([
                'status'=>422,
                'errors'=>$validator->messages(),
            ]);
        }else{
            $category = Category::find($id);
            if($category){
                $category->meta_title = $request->input('meta_title');
                $category->meta_keyword = $request->input('meta_keyword');
                $category->meta_description = $request->input('meta_description');
                $category->slug = $request->input('slug');
                $category->name = $request->input('name');
                if ($request->hasFile('image')) {
                    $path = $category->image;
                    if(File::exists($path)){
                        File::delete($path);
                    }
                    $file = $request->file('image');
                    $extension = $file->getClientOriginalExtension();
                    $filename = time() . '.' . $extension;
                    $file->move('upload/category/', $filename);
                    $category->image = 'upload/category/' . $filename;
                }
                $category->description = $request->input('description');
                $category->collection_id = request('collection_id');
                $category->status = $request->input('status')== true ? '1':'0';
                $category->save();
                return response()->json([
                    'status'=>200,
                    'message'=>'Category Update Successfully'
                ]);
            }
            else
            {
                return response()->json([
                    'status'=>404,
                    'message'=>'No Category ID Found'
                ]);
            }

        }
    }
    public function destroy($id){
        $category = Category::find($id);
        if($category){
            $category->delete();
            return response()->json([
                'status'=>200,
                'message'=>'Category Deleted Successfully'
            ]);
        }
        else{
            return response()->json([
                'status'=>404,
                'message'=>'No Category ID Found'
            ]);
        }
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'meta_title'=>'required|max:191',
            'slug'=>'required|max:191',
            'name'=>['required', 'max:191', 'regex:/^[a-zA-Z0-9\s]+$/'],
            'meta_keyword'=>'required',
            'meta_description'=>'required',
            'description'=>'required',
        ],[
            'meta_title.required'=>'Meta Title Required',
            'slug.required'=>'Slug Required',
            'name.required'=>'Name Required',
            'name.regex'=>'Name Must Be At Least 2 Character',
            'meta_keyword.required'=>'Meta Keyword Required',
            'meta_description.required'=>'Meta Description Required',
            'description.required'=>'Description Required',

        ]);
        if($validator->fails()){
            return response()->json([
                'status'=>422,
                'errors'=>$validator->messages(),
            ]);
        }else{
            $category = new Category();
            $category->meta_title = $request->input('meta_title');
            $category->meta_keyword = $request->input('meta_keyword');
            $category->meta_description = $request->input('meta_description');
            $category->slug = $request->input('slug');
            $category->name = $request->input('name');

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move('upload/category/', $filename);
                $category->image = 'upload/category/' . $filename;
            }

            $category->collection_id = $request->input('collection_id');
            $category->description = $request->input('description');
            $category->status = $request->input('status') ? '1':'0';
            $category->save();
            return response()->json([
                'status'=>200,
                'message'=>'Category Added Successfully'
            ]);
        }


    }
}
