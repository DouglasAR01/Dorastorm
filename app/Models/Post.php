<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Conner\Tagging\Taggable;

class Post extends Model
{
    use HasFactory;
    use Taggable;

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

    public function save(array $options  = [])
    {
        $slug = Str::slug($this->title, '-');
        $isUnique = Post::where('slug', '=', $slug)->first();
        if (!empty($isUnique) && $this->id != $isUnique->id) {
            $slug = Str::slug($this->title .'-'. Carbon::now()->subSeconds(1), '-');
        }
        $this->slug = $slug;
        return parent::save($options);
    }
}
