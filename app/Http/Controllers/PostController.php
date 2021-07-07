<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    private $validation_rules = [
        'title' => 'required|string|min:5|max:190',
        'description' => 'required|string|min:5|max:300',
        'content' => 'required|string',
        'banner' => 'nullable|string|max:191',
        'visible' => 'boolean',
        'private' => 'boolean'
    ];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Post::where($this->indexShowConditions());
        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($query) use ($q) {
                $query->where('title', 'LIKE', "%$q%")
                    ->orWhere('content', 'LIKE', "%$q%");
            });
        }
        return PostResource::collection($query->orderBy('created_at', 'desc')->paginate(15));
    }

    public function myPostsIndex(Request $request){
        if ($request->user()->cannot('create', Post::class)){
            abort(403);
        }
        $query = Post::where('user_id', $request->user()->id);
        return PostResource::collection($query->orderBy('created_at', 'desc')->paginate(15));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->user()->cannot('create', Post::class)) {
            abort(403);
        }
        $data = $request->validate($this->validation_rules);
        $newPost = Post::make($data);
        $newPost->private = $data['private'] ?? false;
        $newPost->user_id = $request->user()->id;
        $newPost->save();
        return new PostResource($newPost);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $slug)
    {
        $post = Post::where('slug', '=', $slug)->first();
        if (empty($post)) {
            abort(404);
        }
        if ($post->visible && !$post->private) {
            return new PostResource($post);
        }
        // If the post is visible and its private the user must be authenticated 
        if ($post->visible && $post->private && Auth::check()) {
            return new PostResource($post);
        }
        // If the post is not visible the user should not see it unless it's the owner of the post or have permissions of updating
        if (!$post->visible && Auth::check() && $request->user()->can('update', $post)) {
            return new PostResource($post);
        }
        abort(403);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        if ($request->user()->cannot('update', $post)) {
            abort(403);
        }
        return new PostResource($post);
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
        if ($request->user()->cannot('update', $post)) {
            abort(403);
        }
        $data = $request->validate($this->validation_rules);

        $post->title = $data['title'];
        $post->content = $data['content'];
        $post->description = $data['description'];
        $post->visible = $data['visible'] ?? $post->visible;
        $post->private = $data['private'] ?? $post->private;
        $url = config('filesystems.disks.public.url') . '/';
        if (!empty($post->banner)) {
            $url .= $post->banner;
        }
        if ($data['banner'] != $url) {
            $post->banner = $data['banner'];
        }
        $post->save();
        return new PostResource($post);
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
        if ($request->user()->cannot('delete', $post)) {
            abort(403);
        }
        $post->delete();
    }

    private function indexShowConditions()
    {
        $condition = [['visible', '=', 1]];
        if (!Auth::check()) {
            array_push($condition, ['private', '=', 0]);
        }
        return $condition;
    }
}
