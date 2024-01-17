<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
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

    $formattedEvents = $events->map(function ($event) {
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
    });

    return response()->json($formattedEvents);
}

    public function create()
    {
        return view('create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'entry_price' => 'required|numeric',
            'exit_price' => 'required|numeric',
            'profit' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'comment' => 'nullable|string',
        ]);

        $event = new Event([
            'title' => $request->input('title'),
            'entry_price' => $request->input('entry_price'),
            'exit_price' => $request->input('exit_price'),
            'profit' => $request->input('profit'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'comment' => $request->input('comment'),
        ]);

        auth()->user()->event()->save($event);

        return response()->json(['message' => 'Event created successfully', 'event' => $event]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'entry_price' => 'required|numeric',
            'exit_price' => 'required|numeric',
            'profit' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'comment' => 'nullable|string',
        ]);

        $event = Event::findOrFail($id);
        if ($event->user_id === auth()->id()) {
        $event->update([
            'title' => $request->input('title'),
            'entry_price' => $request->input('entry_price'),
            'exit_price' => $request->input('exit_price'),
            'profit' => $request->input('profit'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'comment' => $request->input('comment'),
        ]);

        return response()->json(['message' => 'Event updated successfully', 'event' => $event]);
        }
        return response()->json(['message' => 'Unauthorized'], 401);
    }


    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        return response()->json(['message' => 'Event deleted successfully']);
    }
}
