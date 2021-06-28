<?php


namespace App\Repositories;


use App\Models\Comment;
use Illuminate\Support\Facades\DB;

class CommentRepository extends BaseRepository
{

    public function create(array $attributes)
    {
        return DB::transaction(function () use ($attributes) {

            $created = Comment::query()->create([
                'title' => data_get($attributes, 'title'),
                'body' => data_get($attributes, 'body'),
                'user_id' => data_get($attributes, 'user_id'),
                'post_id' => data_get($attributes, 'post_id'),
            ]);
            return $created;
        });
    }

    /**
     * @param Comment $comment
     * @param array $attributes
     * @return mixed
     */
    public function update($comment, array $attributes)
    {
        return DB::transaction(function () use($comment, $attributes) {
            $updated = $comment->update([
                'title' => data_get($attributes, 'title', $comment->title),
                'body' => data_get($attributes, 'body', $comment->body),
                'user_id' => data_get($attributes, 'user_id', $comment->user_id),
                'post_id' => data_get($attributes, 'post_id', $comment->post_id),
            ]);

            if(!$updated){
                throw new \Exception('Failed to update comment');
            }
            return $comment;
        });
    }

    /**
     * @param Comment $comment
     * @return mixed
     */
    public function forceDelete($comment)
    {
        return DB::transaction(function () use($comment) {
            $deleted = $comment->forceDelete();

            if(!$deleted){
                throw new \Exception("cannot delete comment.");
            }

            return $deleted;
        });



    }
}