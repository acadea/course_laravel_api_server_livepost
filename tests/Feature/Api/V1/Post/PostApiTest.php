<?php

namespace Tests\Feature\Api\V1\Post;

use App\Events\Models\Post\PostCreated;
use App\Events\Models\Post\PostDeleted;
use App\Events\Models\Post\PostUpdated;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class PostApiTest extends TestCase
{
    use RefreshDatabase;

    protected $uri = '/api/v1/posts';

    public function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->make();
        $this->actingAs($user);
    }

    public function test_index()
    {
        // load data in db
        $posts = Post::factory(10)->create();
        $postIds = $posts->map(fn ($post) => $post->id);

        // call index endpoint
        $response = $this->json('get', $this->uri);

        // assert status
        $response->assertStatus(200);
        // verify records
        $data = $response->json('data');
        collect($data)->each(fn ($post) => $this->assertTrue(in_array($post['id'], $postIds->toArray())));
    }

    public function test_show()
    {
        $dummy = Post::factory()->create();
        $response = $this->json('get', $this->uri . '/' . $dummy->id);

        $result = $response->assertStatus(200)->json('data');

        $this->assertEquals(data_get($result, 'id'), $dummy->id, 'Response ID not the same as model id.');
    }


    public function test_create()
    {
        Event::fake();
        $dummy = Post::factory()->make();

        $dummyUser = User::factory()->create();

        $response = $this->json('post', $this->uri, array_merge($dummy->toArray(), ['user_ids' => [$dummyUser->id]]));

        $result = $response->assertStatus(201)->json('data');
        Event::assertDispatched(PostCreated::class);
        $result = collect($result)->only(array_keys($dummy->getAttributes()));

        $result->each(function ($value, $field) use($dummy){
            $this->assertSame(data_get($dummy, $field), $value, 'Fillable is not the same.');
        });
    }

    public function test_update()
    {
        $dummy = Post::factory()->create();
        $dummy2 = Post::factory()->make();
        Event::fake();
        $fillables = collect((new Post())->getFillable());

        $fillables->each(function ($toUpdate) use($dummy, $dummy2){
            $response = $this->json('patch', $this->uri . '/' . $dummy->id, [
                $toUpdate => data_get($dummy2, $toUpdate),
            ]);

            $result = $response->assertStatus(200)->json('data');
            Event::assertDispatched(PostUpdated::class);
            $this->assertSame(data_get($dummy2, $toUpdate), data_get($dummy->refresh(), $toUpdate),'Failed to update model.');
        });
    }

    public function test_delete()
    {
        Event::fake();
        $dummy = Post::factory()->create();

        $response = $this->json('delete', $this->uri . '/'.$dummy->id);

        $result = $response->assertStatus(200);
        Event::assertDispatched(PostDeleted::class);
        $this->expectException(ModelNotFoundException::class);
        Post::query()->findOrFail($dummy->id);

    }

}
