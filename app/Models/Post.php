<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Conner\Tagging\Taggable;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Support\Facades\Auth;

class Post extends Model
{
    use HasFactory;
    use Taggable;
    use Sluggable;
    use SluggableScopeHelpers;

    protected $guarded = [
        'private'
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'id' => 'nan',
            'name' => 'ANON',
        ]);
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }
}
