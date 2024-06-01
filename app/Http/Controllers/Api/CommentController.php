<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function index(){
        $comment = Comment::all();
        return response()->json([
            'status' => 200,
            'comment' => $comment
        ]);
    }
    public function show_comment($id)
    {
        $comment = Comment::where('product_id', $id)->get();
        return response()->json([
            'status' => 200,
            'comment' => $comment
        ]);
    }
    public function getProductIdBySlug($slug)
    {
        $product = Product::where('slug', $slug)->first();
        if($product){
            return response()->json([
                'status' => 200,
                'productId' => $product->id
            ]);
        }else {
            return response()->json([
                'status' => 404,
                'message' => 'Product not found'
            ]);
        }
    }
    public function store(Request $request){

        $validator = Validator::make($request->all(),[
            'title' => 'required',
            'content' => 'required',
            'rating' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        if($validator->fails()){
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ]);
        }else{
            if(auth('sanctum')->check()){
                $user = auth('sanctum')->user()->id;
                $product_id = $request->input('product_id');
                $post_id = $request->input('post_id');
                $title = $request->input('title');
                $content = $request->input('content');
                $rating = $request->input('rating');
                $image = $request->file('image');

                $productCheck = Product::find($product_id);
                if ($productCheck){
                    if(Comment::where('product_id', $product_id)->where('user_id', $user)->exists()){
                        return response()->json([
                            'status' => 409,
                            'errors' => 'Comment already exist'
                        ]);
                    }else{
                        $comment = new Comment();
                        $comment->user_id = $user;
                        $comment->product_id = $product_id;
                        $comment->post_id = $post_id;
                        $comment->title = $title;
                        $comment->content = $content;
                        $comment->rating = $rating;
                        if ($request->hasFile('image')) {
                            $file = $request->file('image');
                            $extension = $file->getClientOriginalExtension();
                            $filename = time() . '.' . $extension;
                            $file->move('upload/comment/', $filename);
                            $comment->image = 'upload/comment/' . $filename;
                        }
                        $comment->save();
                        return response()->json([
                            'status' => 200,
                            'message' => "Add Comment Successfully"
                        ]);
                    }
                }else{
                    return response()->json([
                        'status' => 404,
                        'message' => 'Product not found'
                    ]);
                }
            }else{
                return response()->json([
                    'status' => 401,
                    'message' => 'Login to comment'
                ]);
            }
        }
    }
}
