<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

/**
 * Purchase a ticket for an event if slots are available and the event is ongoing.
 *
 * @param int $eventId
 * @return \Illuminate\Http\RedirectResponse
 * 
 * @author Ho Luu Duc
 * Date: 22-08-2025
 */
class TicketController extends Controller
{
    public function buy($eventId)
    {
        if (!Event::where('id', $eventId)->exists()) {
            return back()->withErrors(['event' => 'Event not found']);
        }

        $event = Event::find( $eventId);
        $remaining_slots = $event->total_slots - $event->booked_slots;
        $cost = $event->cost;

        if ($remaining_slots <= 0) {
            return back()->with('error', 'There are not enough slots left.');
        }

        if ($event->finish_time < now()) {
            return back()->with('error', 'This event has already ended.');
        }

        Ticket::create([
            'user_id' => Auth::id(),
            'event_id' => $eventId,
            'quantity' => 1,
            'cost' => $cost,
            'status' => 'paid',
            'purchased_at' => now(),
        ]);

        $event->booked_slots += 1;
        $event->save();

        return back()->with('success', 'Ticket purchased successfully.');
    }
}
