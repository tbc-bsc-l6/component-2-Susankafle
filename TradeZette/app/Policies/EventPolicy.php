<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Event;

class EventPolicy
{
    public function update(User $user, Event $event)
    {
        // Check if the authenticated user can update the event
        return $user->id === $event->user_id;
    }

    public function delete(User $user, Event $event)
    {   
        \Log::info("User {$user->id} attempting to delete Event {$event->id}");
        // Check if the authenticated user can delete the event
        return $user->id === $event->user_id;
    }
}
