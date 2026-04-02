<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CalendarEvent;
use Illuminate\Http\Request;

class CalendarEventController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.calender.calender');
    }
    public function fetch(Request $request)
    {
        $adminId = $request->session()->get('ADMIN_ID');
        $events = CalendarEvent::where('user_id', $adminId)->get();

        return response()->json(
            $events->map(function ($event) {

                return [
                    'id'              => $event->id,
                    'title'           => $event->title,
                    'start'           => $event->start_time,   // ← start_time
                    'end'             => $event->end_time,     // ← end_time
                    'backgroundColor' => $event->backgroundcolor, // ← from DB
                    'borderColor'     => $event->bordercolor,     // ← from DB
                    'textColor'       => $event->textcolor,       // ← from DB
                    'extendedProps'   => [
                        'type'        => $event->type,
                        'description' => $event->description,
                        'status'      => $event->status,       // ← status too
                    ]
                ];
            })
        );
    }
    public function storeEvent(Request $request)
    {
        $adminId = $request->session()->get('ADMIN_ID');
        $event = CalendarEvent::create([
            'user_id' => $adminId,
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type ?? 'task',
            'stsrt' => $request->start,
            'end' => $request->end,
        ]);
        return response()->json($event);
    }
    public function store(Request $request)
    {
        $request->validate([
            'title'      => 'required|string|max:255',
            'type'       => 'required|string',
            'start_time' => 'required',
            'end_time'   => 'required|after_or_equal:start_time',
        ]);

        $adminId = $request->session()->get('ADMIN_ID'); // ← always from session

        $data = [
            'user_id'         => $adminId,  // ← never trust user input for this
            'title'           => $request->title,
            'description'     => $request->description,
            'type'            => $request->type,
            'start_time'      => $request->start_time,
            'end_time'        => $request->end_time,
            'all_day'         => $request->has('all_day') ? 1 : 0,
            'backgroundcolor' => $request->backgroundcolor,
            'bordercolor'     => $request->bordercolor,
            'textcolor'       => $request->textcolor,
            'status'          => $request->status,
        ];

        if ($request->filled('event_id')) {
            // Scope update to this admin only — prevents editing other users' events
            $event = CalendarEvent::where('id', $request->event_id)
                ->where('user_id', $adminId)  // ← security check
                ->firstOrFail();

            $event->update($data);
            $message = 'Event updated successfully!';
        } else {
            CalendarEvent::create($data);
            $message = 'Event added successfully!';
        }

        return redirect()->back()->with('success', $message);
    }
    // Delete event (API)
    public function deleteEvent(Request $request, $id)
    {
        $adminId = session('ADMIN_ID');

        $event = CalendarEvent::where('id', $id)
            ->where('user_id', $adminId)
            ->first();

        if (!$event) {
            return response()->json([
                'success' => false,
                'message' => 'Event not found'
            ], 404);
        }

        $event->delete();

        return response()->json([
            'success' => true,
            'message' => 'Event deleted successfully'
        ]);
    }
}
