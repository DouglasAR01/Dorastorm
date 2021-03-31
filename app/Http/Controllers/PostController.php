<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    private $validation_rules = [
        'title' => 'required|string|min:5|max:190',
        'content' => 'required|string',
        'visible' => 'boolean',
        'private' => 'boolean'
    ];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Pagination is required
        return Post::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->user()->cannot('create',Post::class)){
            abort(403);
        }
        $data = $request->validate($this->validation_rules);
        $newPost = Post::make($data);
        $newPost->private = $data['private'] ?? false;
        $newPost->user_id = $request->user()->id;
        $newPost->save();
        return new PostResource($newPost) ;
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $post = Post::where('slug', '=', $slug)->first();
        if (empty($post)) {
            abort(404);
        }
        return new PostResource($post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        if($request->user()->cannot('update', $post)){
            abort(403);
        }
        $data = $request->validate($this->validation_rules);
        
        $post->title = $data['title'];
        $post->content = $data['content'];
        $post->visible = $data['visible'] ?? $post->visible;
        $post->private = $data['private'] ?? $post->private;
        $post->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        if($request->user()->cannot('delete', $post)){
            abort(403);
        }
        $post->delete();
    }

}
