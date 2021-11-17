<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SsrController extends Controller
{
    protected $globalParams;

    public function __construct()
    {
        $this->globalParams = [
            'locales' => config('app.supported_locales')
        ];
    }

    public function ssrPostRead(Request $request, $slug)
    {
        $post = PostController::getPost($request, $slug);
        return $this->response([
            'meta' => [
                'title' => $post->title,
                'description' => $post->description
            ],
            'og' => [
                'og:url' => $request->url(),
                'og:title' => $post->title,
                'og:description' => $post->description,
                'og:type' => 'website',
                'og:image' => asset(config('filesystems.disks.public.url') . '/' . $post->banner) ?? null,
            ],
            'twitter' => [
                'twitter:card' => 'summary',
                'twitter:site' => '@nuwebsco'
            ]
        ]);
    }

    public function ssrIndex()
    {
        return $this->response();
    }

    private function response($aditionalParams = [])
    {
        $params = array_merge($this->globalParams, $aditionalParams);
        return view('index', $params);
    }
}
