<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use Database\Seeders\Traits\DisableForeignKeys;
use Database\Seeders\Traits\TruncateTable;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    use DisableForeignKeys, TruncateTable;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();

        $this->truncate('comments');

        Comment::factory(3)
//            ->for(Post::factory(1), 'post')
            ->create();

        $this->enableForeignKeys();
    }
}
