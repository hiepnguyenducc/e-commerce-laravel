<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Color;
use App\Models\Product;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function category(){
        $category = Category::withCount('product')
            ->where('status', '0')
            ->get();
        return response()->json([
            'status' => 200,
            'category' => $category
        ]);
    }
    public function color()
    {
        $color = Color::withCount('product')->where('status','0')->get();
        return response()->json([
            'status' => 200,
            'color' => $color
        ]);
    }
    public function brand()
    {
        $brand = Brand::withCount('product')->where('status','0')->get();
        return response()->json([
            'status' => 200,
            'brand' => $brand
        ]);
    }
    public function price_product(){
        $productUnder50 = Product::whereBetween('original_price',[0,50])->where('status','0')->get();
        $product50to100 = Product::whereBetween('original_price',[50,100])->where('status','0')->get();
        $product100to150 = Product::whereBetween('original_price',[100,150])->where('status','0')->get();
        $product150to200 =Product::whereBetween('original_price',[150,200])->where('status','0')->get();
        $productAbove200 =Product::where('original_price','>',200)->where('status','0')->get();
        return response()->json([
            'status' => 200,
            'productUnder50' => $productUnder50,
            'product50to100' => $product50to100,
            'product100to150' => $product100to150,
            'product150to200' => $product150to200,
            'productAbove200' => $productAbove200
        ]);
    }
    public function product(){
        $product = Product::all();
        return response()->json([
            'status' => 200,
            'product' => $product
        ]);
    }
    public function product_category($id){
        if(!is_array($id)) {
            $id = [$id];
        }
        if(empty($id)) {
            return response()->json([
                'status' => 200,
                'message' => 'No product category selected'
            ]);
        }
        $products = Product::whereIn('category_id', $id)->get();
        return response()->json([
            'status' => 200,
            'product' => $products
        ]);
    }
    public function product_detail($slug){
        $product = Product::where('slug', $slug)->first();
        if ($product) {
            return response()->json([
                'status' => 200,
                'product' => $product
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Product not found'
            ]);
        }
    }
    public function all_color()
    {
        $color = Color::all();
        return response()->json([
            'status' => 200,
            'color' => $color
        ]);
    }

    public function collection()
    {
        $collection = Collection::where('status','0')->get();
        return response()->json([
            'status' => 200,
            'collection' => $collection
        ]);
    }
    public function category_collection($id)
    {
        $category = Category::withCount('product')->where('collection_id', $id)->get();
        if ($category->isNotEmpty()) {
            return response()->json([
                'status' => 200,
                'category' => $category
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No categories found for the specified collection'
            ]);
        }
    }
    public function product_by_collection($id)
    {
        $product = Product::where('collection_id', $id)->get();
        if ($product->isNotEmpty()) {
            return response()->json([
                'status' => 200,
                'product' => $product
            ]);
        }
    }
    public function category_by_slug($slug)
    {
        $category = Category::where('slug', $slug)->get();
        if($category){
            return response()->json([
                'status' => 200,
                'category' => $category
            ]);
        }else{
            return response()->json([
                'status' => 404,
                'message' => 'Category not found'
            ]);
    }

    }

    public function product_by_category($slug)
    {
        $category = Category::where('slug', $slug)->first();
        if ($category) {
            $product = Product::where('category_id', $category->id)->get();

            return response()->json([
                'status' => 200,
                'product' => $product
            ]);
        } else {

            return response()->json([
                'status' => 404,
                'message' => 'Category not found'
            ], 404);
        }

    }





}
