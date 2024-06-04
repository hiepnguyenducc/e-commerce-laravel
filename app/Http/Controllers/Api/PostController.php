<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function index(){
        $post = Post::all();
        return response()->json([
            'status' => 200,
            'post' => $post
        ]);
    }
    public function all_post()
    {
        $post = Post::all();
        return response()->json([
            'status' => 200,
            'post' => $post
        ]);
    }
    public function postbyslug($slug)
    {
        $post = Post::where('slug', $slug)->get();
        return response()->json([
            'status' => 200,
            'post' => $post
        ]);
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'content'=>'required',
            'slug'=>'required|max:191',
            'title'=>'required|max:191',
        ]);
        if($validator->fails()){
            return response()->json([
                'status'=>422,
                'errors'=>$validator->messages(),
            ]);
        }else{
            $post = new Post();

            $post->content = $request->input('content');
            $post->slug = $request->input('slug');
            $post->title = $request->input('title');

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move('upload/post/', $filename);
                $post->image = 'upload/post/' . $filename;
            }

            $post->save();
            return response()->json([
                'status'=>200,
                'message'=>'Post Added Successfully'
            ]);
        }
    }
    public function destroy($id){
        $post = Post::find($id);
        if($post){
            $post->delete();
            return response()->json([
                'status'=>200,
                'message'=>'Post Deleted Successfully'
            ]);
        }
        else{
            return response()->json([
                'status'=>404,
                'message'=>'No Post ID Found'
            ]);
        }
    }
    public function update(Request $request,$id){
        $validator = Validator::make($request->all(),[
            'content'=>'required',
            'slug'=>'required|max:191',
            'title'=>'required|max:191',
        ]);
        if($validator->fails()){
            return response()->json([
                'status'=>422,
                'errors'=>$validator->messages(),
            ]);
        }else{
            $post = Post::find($id);
            if($post){
                $post->content = $request->input('content');
                $post->slug = $request->input('slug');
                $post->title = $request->input('title');
                if ($request->hasFile('image')) {
                    $path = $post->image;
                    if(File::exists($path)){
                        File::delete($path);
                    }
                    $file = $request->file('image');
                    $extension = $file->getClientOriginalExtension();
                    $filename = time() . '.' . $extension;
                    $file->move('upload/post/', $filename);
                    $post->image = 'upload/post/' . $filename;
                }

                $post->save();
                return response()->json([
                    'status'=>200,
                    'message'=>'Post Update Successfully'
                ]);
            }
            else
            {
                return response()->json([
                    'status'=>404,
                    'message'=>'No Post ID Found'
                ]);
            }

        }
    }
    public function edit($id){
        $post = Post::find($id);
        if($post){
            return response()->json([
                'status'=>200,
                'post'=>$post,
            ]);
        }else{
            return response()->json([
                'status'=>404,
                'message'=>'No post Id Found',
            ]);
        }
    }
}
