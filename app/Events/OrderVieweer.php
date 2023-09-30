<?php

namespace App\Events;

use App\Models\Role;
use App\Models\Orders;
use Illuminate\Http\Request;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class OrderVieweer
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $views;
    public function __construct(Orders $order)
    {
        //
        $this -> views = $order;
        if(auth()->user()->role != Role::ADMIN){
        $this -> updateVieweer($this -> views);}
        else{
            
        }
    
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }

    
   public function updateVieweer($views){
        
        $views -> view = $views -> view + 1;
        $views -> save();
    }
}
