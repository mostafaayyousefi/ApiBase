<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:4',
            'text' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }
         $user_id  = auth('api')->user()->id;
         $post = Post::create([
            'title' => $request->title,
            'text' => $request->text,
            'user_id' => $user_id,
        ]);
        return response()->json(
                [
                    'flag' => '1' ,
                    'title' => $post->title ,
                    'message' => 'success' ,
             ],  200);
    }


    public function index(Request $request){
         $user_id  = auth('api')->user()->id;
         $posts = Post::where([ ['user_id',$user_id] ])->paginate(20);
        return response()->json(
                [
                    'flag' => '1' ,
                    'posts' => $posts ,
                    'message' => 'success' ,
             ],  200);
    }


    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
            'title' => 'required|min:4',
            'text' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }
         $user_id  = auth('api')->user()->id;
         $post = Post::where([ ['id',$request->id],['user_id',$user_id], ])->first();

         if($post){
            $post->update([ 'title' => $request->title , 'text'=>$request->text  ]);
            return response()->json(
                    [
                        'flag' => '1' ,
                        'title' => $post->title ,
                        'message' => 'success' ,
                 ],  200);
         } else{

            return response()->json(
                [
                    'flag' => '0' ,
                    'message' => 'error' ,
             ],  401);
         }

    }




    public function destroy(Request $request){





        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }
         $user_id  = auth('api')->user()->id;
         $post = Post::where([ ['id',$request->id],['user_id',$user_id], ])->first();

         if($post){
            Post::destroy($request->id);
            return response()->json(
                    [
                        'flag' => '1' ,
                        'title' => $post->title ,
                        'message' => 'success_delete' ,
                 ],  200);
         } else{

            return response()->json(
                [
                    'flag' => '0' ,
                    'message' => 'error' ,
             ],  401);
         }

    }




}
