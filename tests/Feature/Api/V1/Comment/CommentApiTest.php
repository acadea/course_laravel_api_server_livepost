<?php

namespace Tests\Feature\Api\V1\Comment;

use App\Events\Models\Comment\CommentCreated;
use App\Events\Models\Comment\CommentDeleted;
use App\Events\Models\Comment\CommentUpdated;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CommentApiTest extends TestCase
{
    use RefreshDatabase;

    protected $uri = '/api/v1/comments';

    public function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->make();
        $this->actingAs($user);
    }

    public function test_index()
    {
        // load data in db
        $comments = Comment::factory(10)->create();
        $commentIds = $comments->map(fn($comment) => $comment->id);

        // call index endpoint
        $response = $this->json('get', $this->uri);

        // assert status
        $response->assertStatus(200);
        // verify records
        $data = $response->json('data');
        collect($data)->each(fn($comment) => $this->assertTrue(in_array($comment['id'], $commentIds->toArray())));
    }

    public function test_show()
    {
        $dummy = Comment::factory()->create();
        $response = $this->json('get', $this->uri . '/' . $dummy->id);

        $result = $response->assertStatus(200)->json('data');

        $this->assertEquals(data_get($result, 'id'), $dummy->id, 'Response ID not the same as model id.');
    }


    public function test_create()
    {
        Event::fake();
        $dummy = Comment::factory()->make();

        $response = $this->json('post', $this->uri, $dummy->toArray());

        $result = $response->assertStatus(201)->json('data');
        Event::assertDispatched(CommentCreated::class);
        $result = collect($result)->only(array_keys($dummy->getAttributes()));

        $result->each(function ($value, $field) use ($dummy) {
            $this->assertSame(data_get($dummy, $field), $value, 'Fillable is not the same.');
        });
    }

    public function test_update()
    {
        $dummy = Comment::factory()->create();
        $dummy2 = Comment::factory()->make();
        Event::fake();
        $fillables = collect((new Comment())->getFillable());

        $fillables->each(function ($toUpdate) use ($dummy, $dummy2) {
            $response = $this->json('patch', $this->uri . '/' . $dummy->id, [
                $toUpdate => data_get($dummy2, $toUpdate),
            ]);

            $result = $response->assertStatus(200)->json('data');
            Event::assertDispatched(CommentUpdated::class);
            $this->assertEquals(data_get($dummy2, $toUpdate), data_get($dummy->refresh(), $toUpdate), 'Failed to update model.');
        });
    }

    public function test_delete()
    {
        Event::fake();
        $dummy = Comment::factory()->create();

        $response = $this->json('delete', $this->uri . '/' . $dummy->id);

        $result = $response->assertStatus(200);
        Event::assertDispatched(CommentDeleted::class);
        $this->expectException(ModelNotFoundException::class);
        Comment::query()->findOrFail($dummy->id);

    }

}
