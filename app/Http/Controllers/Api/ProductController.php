<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductColor;
use Illuminate\Support\Facades\File;

use http\Env\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request){
        $product = Product::all();

        return response()->json([
            'status'=>200,
            'product'=>$product,
        ]);
    }
    public function all_product_new(Request $request){
        $product = Product::orderBy('created_at', 'desc')->get();
        return response()->json([
            'status'=>200,
            'product'=>$product,
        ]);
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'category_id'=>'required|max:191',
            'slug'=>'required|max:191',
            'name'=>'required|max:191',
            'meta_title'=>'required|max:20',
            'brand_id'=>'required|max:20',
            'original_price'=>'required|max:20',
            'quantity'=>'required|max:4',
            'image'=>'required|image|mimes:jpeg,png,jpg,mp4|max:2048',
        ]);
        if($validator->fails()){
            return response()->json([
                'status'=> 422,
                'errors'=>$validator->messages(),
            ]);
        }
        else{
        $product = new Product();
        $product->category_id = $request->input('category_id');
        $product->slug = $request->input('slug');
        $product->name = $request->input('name');
            $product->description = $request->input('description');
            $product->meta_title = $request->input('meta_title');
            $product->meta_keyword = $request->input('meta_keyword');
            $product->meta_description = $request->input('meta_description');
            $product->brand_id = $request->input('brand_id');
            $product->size_id = $request->input('size_id');
            $product->color_id = $request->input('color_id');
            $product->selling_price = $request->input('selling_price');
            $product->original_price = $request->input('original_price');
            $product->quantity = $request->input('quantity');

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move('upload/product/', $filename);
                $product->image = 'upload/product/' . $filename;
            }

//            if($request->hasFile('image')){
//                $uploadPath = 'upload/product/';
//                foreach ($request->file('image') as $imageFile){
//                    $extention = $imageFile->getClientOriginalExtension();
//                    $fileName = time().'.'.$extention;
//                    $imageFile->move($uploadPath,$fileName);
//                    $finalImagePathName = $uploadPath.$fileName;
//                    $product->productImage()->create([
//                        'product_id'=>$product->id,
//                        'image'=>$finalImagePathName,
//                    ]);
//                }
//            }
//

//            if($request->color_id){
//                foreach ($request->color_id as $key=> $color){
//                    $product->productColor()->create([
//                        'product_id'=>$product->id,
//                        'color_id'=>$color,
//                        'quantity'=>$request->color_quantity[$key] ?? 0
//                    ]);
//                }
//            }

            $product->featured = $request->input('featured') == true ? '1' : '0';
            $product->popular = $request->input('popular') == true ? '1' : '0';
            $product->sale = $request->input('sale') == true ? '1' : '0';
            $product->sale_start_date = $request->input('sale_start_date');
            $product->sale_end_date = $request->input('sale_end_date');
            $product->collection_id = $request->input('collection_id');
            $product->status = $request->input('status') == true ? '1' : '0';
            $product->save();

            $product->save();
        return response()->json([
            'status'=>200,
            'message'=>'Product Add Successfully'
        ]);
        }
    }
    public function destroy($id){
        $product = Product::find($id);
        if($product){
            $product->delete();
            return response()->json([
                'status'=>200,
                'message'=>'Product Deleted Successfully'
            ]);
        }
        else{
            return response()->json([
                'status'=>404,
                'message'=>'No Product Not Found'
            ]);
        }
    }
    public  function edit($id){
        $product = Product::find($id);
        if($product){
            return response()->json([
                'status'=>200,
                'product'=>$product,
            ]);
        }
        else{
            return response()->json([
                'status'=>404,
                'message'=>'No Product Found',
            ]);
        }
    }
    public function update(Request $request,$id){
        $validator = Validator::make($request->all(),[
            'category_id'=>'required|max:191',
            'slug'=>'required|max:191',
            'name'=>'required|max:191',
            'meta_title'=>'required|max:20',

            'original_price'=>'required|max:20',
            'quantity'=>'required|max:20',
        ]);
        if($validator->fails()){
            return response()->json([
                'status'=> 422,
                'errors'=>$validator->messages(),
            ]);
        }
        else{
            $product = Product::find($id);
            if($product){
                $product->category_id = $request->input('category_id');
                $product->slug = $request->input('slug');
                $product->name = $request->input('name');
                $product->description = $request->input('description');
                $product->meta_title = $request->input('meta_title');
                $product->meta_keyword = $request->input('meta_keyword');
                $product->meta_description = $request->input('meta_description');
                $product->brand_id = $request->input('brand_id');
                $product->selling_price = $request->input('selling_price');
                $product->original_price = $request->input('original_price');
                $product->quantity = $request->input('quantity');
                $product->size_id = $request->input('size_id');
                $product->color_id = $request->input('color_id');

                if ($request->hasFile('image')) {
                    $path = $product->image;
                    if(File::exists($path)){
                        File::delete($path);
                    }
                    $file = $request->file('image');
                    $extension = $file->getClientOriginalExtension();
                    $filename = time() . '.' . $extension;
                    $file->move('upload/product/', $filename);
                    $product->image = 'upload/product/' . $filename;
                }

                $product->featured = $request->input('featured') == true ? '1' : '0';
                $product->popular = $request->input('popular') == true ? '1' : '0';
                $product->sale = $request->input('sale') == true ? '1' : '0';
                $product->sale_start_date = $request->input('sale_start_date');
                $product->sale_end_date = $request->input('sale_end_date');
                $product->collection_id = $request->input('collection_id');
                $product->status = $request->input('status') == true ? '1' : '0';

                $product->update();

                $product->save();
                return response()->json([
                    'status'=>200,
                    'message'=>'Product Update Successfully'
                ]);
            }
            else{
                return response()->json([
                    'status'=>404,
                    'message'=>'Product Not Found'
                ]);
            }

        }
    }
}
