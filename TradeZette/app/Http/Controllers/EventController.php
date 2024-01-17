<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function update(Request $request, Event $event)
    {
        $this->authorize('update', $event);

        return $this->storeOrUpdate($request, $event);
    }

    public function getEvents(Request $request)
{
    $request->validate([
        'start' => 'required|date',
    ]);

    $start = $request->input('start');

    // Fetch events only for the authenticated user
    $userEvents = Auth::user()->event()
        ->where(function ($query) use ($start) {
            $query->where('start_date', '>=', $start);
        })
        ->get();

    $formattedEvents = $userEvents->map(fn ($event) => $this->formatEvent($event));

    return response()->json($formattedEvents);
}

    public function store(Request $request)
    {
        $event = new Event($this->validateEventData($request));
        Auth::user()->event()->save($event);

        return response()->json(['message' => 'Event created successfully', 'event' => $this->formatEvent($event)]);
    }

    public function destroy(Event $event)
    {
        $this->authorize('delete', $event);
        

        $event->delete();

        return response()->json(['message' => 'Event deleted successfully']);
    }

    private function storeOrUpdate(Request $request, Event $event)
    {
        $this->authorize('update', $event);

        // Validate the request data
        $validatedData = $this->validateEventData($request);

        // Update the event with the validated data
        try {
            $event->update($validatedData);
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Error updating event: ' . $e->getMessage());

            // Return a response with an error message
            return response()->json(['error' => 'Error updating event. Please try again.'], 500);
        }

        return response()->json(['message' => 'Event updated successfully', 'event' => $this->formatEvent($event)]);
    }


    private function validateEventData(Request $request)
    {
        return $request->validate([
            'title' => 'required',
            'entry_price' => 'required|numeric',
            'exit_price' => 'required|numeric',
            'start_date' => 'required|date',
            'comment' => 'nullable|string',
        ]);
    }

    private function formatEvent(Event $event)
    {
        return [
            'id' => $event->id,
            'title' => $event->title,
            'entry_price' => $event->entry_price,
            'exit_price' => $event->exit_price,
            'start' => $event->start_date,
            'comment' => $event->comment,
        ];
    }
}
