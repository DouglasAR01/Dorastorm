<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Http\Resources\PostsTags;
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
        'tags' => 'nullable|string',
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
        // Check if the user is trying to get their own posts
        if ($request->filled('mine')) {
            if (!Auth::check()) {
                abort(401);
            }
            if ($request->user()->cannot('create', Post::class)) {
                abort(403);
            }
            $query = Post::where('user_id', $request->user()->id);
            return $this->executeIndexQuery($request, $query);
        }
        // The index will always show visible posts
        $query = Post::where('visible', 1);
        // If the request has the p field filled, the index will only show visible and private posts
        $private = 0;
        if ($request->filled('p')) {
            if (!Auth::check()) {
                abort(401);
            }
            $private = 1;
        }
        $query->where('private', $private);
        return $this->executeIndexQuery($request, $query);
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
        if ($request->filled('tags')) {
            $tags = explode(',', $data['tags']);
            $newPost->tag($tags);
        }
        return new PostResource($newPost);
    }

    public static function getPost(Request $request, $slug)
    {
        $post = Post::where('slug', '=', $slug)->first();
        if (empty($post)) {
            abort(404);
        }
        if ($post->visible && !$post->private) {
            return $post;
        }
        // If the post is visible and its private the user must be authenticated 
        if ($post->visible && $post->private && Auth::check()) {
            return $post;
        }
        // If the post is not visible the user should not see it unless he is the owner of the post or have permissions of updating
        if (!$post->visible && Auth::check() && $request->user()->can('update', $post)) {
            return $post;
        }
        abort(403);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $slug)
    {
        return new PostResource($this->getPost($request, $slug));
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
        if (!empty($data['banner']) && $data['banner'] != $url) {
            $post->banner = $data['banner'];
        }
        $post->save();
        if ($request->filled('tags')) {
            $tags = explode(',', $data['tags']);
            $post->retag($tags);
        }
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

    /**
     * Returns all posts related tags
     * 
     * @return App\Http\Resource\PostsTags
     */
    public function getAllTags()
    {
        return PostsTags::collection(Post::existingTags());
    }

    /**
     * Performs the posts query with eager loading
     * 
     * @return App\Http\Resource\PostResource
     */
    private function executeIndexQuery(Request $request, $query)
    {
        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($query) use ($q) {
                $query->where('title', 'LIKE', "%$q%")
                    ->orWhere('content', 'LIKE', "%$q%");
            });
        }
        if ($request->filled('t')) {
            $t = $request->input('t');
            if (strlen($t) > 0) {
                $tags = explode(',', $t);
                $query->withAnyTag($tags);
            }
        }
        if ($request->filled('e')) {
            $e = $request->input('e');
            if (strlen($e) > 0) {
                $exclude = str_replace(',', '|', $e);
                $query->where(function ($query) use ($exclude) {
                    $query->where('title', 'NOT RLIKE', "$exclude")
                        ->where('content', 'NOT RLIKE', "$exclude");
                });
            }
        }
        return PostResource::collection($query->orderBy('created_at', 'desc')->with(['user', 'tagged'])->paginate(15));
    }
}
