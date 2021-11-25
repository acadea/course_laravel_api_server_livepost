<?php

namespace Tests\Feature\Api\V1\User;

use App\Events\Models\User\UserCreated;
use App\Events\Models\User\UserDeleted;
use App\Events\Models\User\UserUpdated;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    protected $uri = '/api/v1/users';

//    public function tearDown(): void
//    {
//        parent::tearDown();
//        dump('heyyaa');
//    }

    public function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->make();
        $this->actingAs($user);
    }
    
    public function test_index()
    {

        // load data in db
        $users = User::factory(10)->create();
        $userIds = $users->map(fn ($user) => $user->id);

        // call index endpoint
        $response = $this->json('get', $this->uri);

        // assert status
        $response->assertStatus(200);
        // verify records
        $data = $response->json('data');
        collect($data)->each(fn ($user) => $this->assertTrue(in_array($user['id'], $userIds->toArray())));
    }

    public function test_show()
    {
        $dummy = User::factory()->create();
        $response = $this->json('get', $this->uri . '/' . $dummy->id);

        $result = $response->assertStatus(200)->json('data');

        $this->assertEquals(data_get($result, 'id'), $dummy->id, 'Response ID not the same as model id.');
    }


    public function test_create()
    {
        Event::fake();
        $dummy = User::factory()->make();

        $response = $this->json('post', $this->uri, $dummy->toArray());

        $result = $response->assertStatus(201)->json('data');
        Event::assertDispatched(UserCreated::class);
        $result = collect($result)->only(array_keys($dummy->getAttributes()));

        $result->each(function ($value, $field) use($dummy){
            $this->assertSame(data_get($dummy, $field), $value, 'Fillable is not the same.');
        });
    }

    public function test_update()
    {
        $dummy = User::factory()->create();
        $dummy2 = User::factory()->make();
        Event::fake();
        $fillables = collect((new User())->getFillable());

        $fillables->each(function ($toUpdate) use($dummy, $dummy2){
            $response = $this->json('patch', $this->uri . '/' . $dummy->id, [
                $toUpdate => data_get($dummy2, $toUpdate),
            ]);

            $result = $response->assertStatus(200)->json('data');
            Event::assertDispatched(UserUpdated::class);
            $this->assertEquals(data_get($dummy2, $toUpdate), data_get($dummy->refresh(), $toUpdate),'Failed to update model.');
        });
    }

    public function test_delete()
    {
        Event::fake();
        $dummy = User::factory()->create();

        $response = $this->json('delete', $this->uri . '/'.$dummy->id);

        $result = $response->assertStatus(200);
        Event::assertDispatched(UserDeleted::class);
        $this->expectException(ModelNotFoundException::class);
        User::query()->findOrFail($dummy->id);

    }

}
