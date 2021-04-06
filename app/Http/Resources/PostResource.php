<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $author = $this->user;
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'content' => $this->content,
            'visible' => $this->visible ?? false,
            'private' => $this->private ?? false,
            'slug' => $this->slug,
            'author' => [
                'id' => $author->id,
                'name' => $author->name
            ],
            'created' => $this->created_at,
            'modified' => $this->updated_at,
        ];
    }
}
