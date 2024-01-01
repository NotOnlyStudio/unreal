<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;
use Illuminate\Broadcasting\PrivateChannel;
class PrivateChat implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    /**
    * The name of the queue connection to use when broadcasting the event.
    *
    * @var string
    */
    public $connection = 'redis';

    /**
    * The name of the queue on which to place the broadcasting job.
    *
    * @var string
    */
    public $queue = 'default';
    public $data;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data =  $data;
        $this->dontBroadcastToCurrentUser();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('room.'.$this->data['chat_id']);
    }

    /*
     * The Event's broadcast name.
     * 
     * @return string
     */
    public function broadcastAs()
    {
        return 'PrivateMessage';
    }
    
    /*
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'message'=> $this->data,
        ];
    }
}