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
            'end' => 'required|date',
        ]);

        $start = $request->input('start');
        $end = $request->input('end');

        $events = Event::whereBetween('start_date', [$start, $end])
            ->orWhereBetween('end_date', [$start, $end])
            ->get();

        $formattedEvents = $events->map(fn ($event) => $this->formatEvent($event));

        return response()->json($formattedEvents);
    }

    public function create()
    {
        return view('create');
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

        $event->update($this->validateEventData($request));

        return response()->json(['message' => 'Event updated successfully', 'event' => $this->formatEvent($event)]);
    }

    private function validateEventData(Request $request)
    {
        return $request->validate([
            'title' => 'required',
            'entry_price' => 'required|numeric',
            'exit_price' => 'required|numeric',
            'profit' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
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
            'profit' => $event->profit,
            'start' => $event->start_date,
            'end' => $event->end_date,
            'comment' => $event->comment,
        ];
    }
}
