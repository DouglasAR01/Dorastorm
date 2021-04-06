<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $editors = User::where('role_id', '=', 2)->get();
        foreach ($editors as $editor){
            $posts = Post::factory()->count(random_int(1,10))->make();
            $editor->posts()->saveMany($posts);
        }
    }
}
