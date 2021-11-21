<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Jaybizzle\LaravelCrawlerDetect\Facades\LaravelCrawlerDetect as Crawler;

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
        if (!Crawler::isCrawler()) {
            return $this->response();
        }
        $post = Post::getPostWithChecks($slug);
        $bannerUrl = !empty($post->banner) ? asset(config('filesystems.disks.public.url') . '/' . $post->banner) : null;

        $responseData = [
            'obj' => $post,
            'meta' => [
                'title' => $post->title,
                'description' => $post->description
            ],
            'og' => [
                'og:url' => $request->url(),
                'og:title' => $post->title,
                'og:description' => $post->description,
                'og:type' => config('meta.og.type'),
                'og:image' =>  $bannerUrl,
            ]
        ];

        if (config('meta.twitter.enabled')) {
            $responseData['twitter'] = [
                'twitter:card' => config('meta.twitter.card'),
                'twitter:site' => config('meta.twitter.site'),
            ];
        }

        return $this->response('ssrobject', $responseData);
    }

    public function ssrIndex()
    {
        return $this->response();
    }

    private function response($view = 'index', $aditionalParams = [])
    {
        $params = array_merge($this->globalParams, $aditionalParams);
        return view($view, $params);
    }
}
