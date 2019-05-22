<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;
use Auth;
use App\User;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $posts = Post::orderBy('created_at', 'DESC')->paginate(15);
        $userNamesWithAvatar = array();
        $users = User::all();

        foreach($posts as $post) {
            foreach($users as $user) {
                if($post->user_id == $user->id) {
                    $userNamesWithAvatar[$post->user_id][0] = $user->avatar;
                    $userNamesWithAvatar[$post->user_id][1] = $user->name;
                }
            }
        }

        if ($request->ajax()) {

            $view = view('postData',compact('posts', 'userNamesWithAvatar'))->render();

            return response()->json(['html'=>$view]);

        }


        return view('posts',compact('posts'),[ 'userNamesWithAvatar' => $userNamesWithAvatar]);
    }


    public function create(Request $request)
    {
        $post = Post::create(['body' => $request->body, 'user_id' => $request->user()->id]);
        $post->save();

        return redirect()->back();
    }


    public function destroy($id)
    {

        $userId = Auth::id();
        $post = Post::where('id', $id)->get();

        if(Auth::User()->role == '2'){
            $post[0]->delete();
            return redirect()->back();

        }
        if($post[0]->user_id !== $userId) {
            return redirect()->back();
        }

        $post[0]->delete();
        return redirect()->back();
    }
}
