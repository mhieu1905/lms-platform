<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Ticket;
use App\Models\User;

class TicketService
{
    /**
     * Check if a user has already booked a ticket for a specific event.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Event $event
     * @return bool
     * 
     * @author Ho Luu Duc
     * Date: 22-08-2025
     */
    public static function hasUserBookedTicket(User $user, Event $event): bool
    {
        return Ticket::where('user_id', $user->id)->where('event_id', $event->id)->exists();
    }

}
