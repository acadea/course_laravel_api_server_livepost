<?php

namespace App\Events\Models\Post;

use App\Models\Post;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PostUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $post;

    /**
     * Create a new event instance.
     * @param Post $model
     * @return void
     */
    public function __construct( $post )
    {
        $this->post = $post;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PresenceChannel('presence.post.' . $this->post->id);
    }

    public function broadcastAs()
    {
        return 'post.updated';
    }

    public function broadcastWith()
    {
        return [
            'post' => $this->post,
        ];
    }
}
