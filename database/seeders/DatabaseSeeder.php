<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Card;
use App\Models\Category;
use App\Models\Desk;
use App\Models\DeskList;
use App\Models\Post;
use App\Models\Tag;
use App\Models\Task;
use Database\Factories\DeskFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Desk::factory(100)->create();
        DeskList::factory(100)->create();
        Card::factory(100)->create();
        Task::factory(100)->create();

        /* Category::factory(20)->create();
        $tags = Tag::factory(200)->create();
        $posts = Post::factory(50)->create();
        foreach ($posts as $post) {
            $tagsIds = $tags->random(5)->pluck('id');
            $post->tags()->attach($tagsIds);
        } */
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
