<?php

namespace Tests\Feature\Api\V1\Post;

use App\Events\Models\Post\PostCreated;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class PostApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_index()
    {
        // load data in db
        $posts = Post::factory(10)->create();
        $postIds = $posts->map(fn ($post) => $post->id);

        // call index endpoint
        $response = $this->json('get', '/api/v1/posts');

        // assert status
        $response->assertStatus(200);
        // verify records
        $data = $response->json('data');
        collect($data)->each(fn ($post) => $this->assertTrue(in_array($post['id'], $postIds->toArray())));
    }

    public function test_show()
    {
        $dummy = Post::factory()->create();
        $response = $this->json('get', '/api/v1/posts/' . $dummy->id);

        $result = $response->assertStatus(200)->json('data');

        $this->assertEquals(data_get($result, 'id'), $dummy->id, 'Response ID not the same as model id.');
    }


    public function test_create()
    {
        Event::fake();
        $dummy = Post::factory()->make();

        $response = $this->json('post', '/api/v1/posts', $dummy->toArray());

        $result = $response->assertStatus(201)->json('data');
        Event::assertDispatched(PostCreated::class);
        $result = collect($result)->only(array_keys($dummy->getAttributes()));

        $result->each(function ($value, $field) use($dummy){
            $this->assertSame(data_get($dummy, $field), $value, 'Fillable is not the same.');
        });
    }

    public function test_update()
    {
        
    }

}
