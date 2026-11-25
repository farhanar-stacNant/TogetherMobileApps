<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * List events with filter (Explore / Home).
     */
    public function index(Request $request)
    {
        $query = Event::with(['creator', 'participants'])
            ->withCount('participants');

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location_name', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        // Default: upcoming events ordered by start_time
        $events = $query->orderBy('start_time', 'asc')->paginate(15);

        // Append whether current user is joined
        $userId = $request->user()?->id;
        $events->getCollection()->transform(function ($event) use ($userId) {
            $event->is_joined = $userId ? $event->participants->contains('id', $userId) : false;
            return $event;
        });

        return response()->json($events);
    }

    /**
     * Get upcoming moments for Home Feed.
     */
    public function upcoming(Request $request)
    {
        $userId = $request->user()?->id;

        $events = Event::with(['creator', 'participants'])
            ->withCount('participants')
            ->where('status', 'upcoming')
            ->orderBy('start_time', 'asc')
            ->take(10)
            ->get();

        $events->transform(function ($event) use ($userId) {
            $event->is_joined = $userId ? $event->participants->contains('id', $userId) : false;
            return $event;
        });

        return response()->json([
            'events' => $events,
        ]);
    }

    /**
     * Store new event (from '+' button in Navigation Bar).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string',
            'image_url' => 'nullable|string',
            'location_name' => 'required|string|max:255',
            'city' => 'nullable|string|max:100',
            'start_time' => 'required|date',
            'max_participants' => 'nullable|integer|min:2',
        ]);

        $event = $request->user()->createdEvents()->create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'category' => strtolower($validated['category']),
            'image_url' => $validated['image_url'] ?? null,
            'location_name' => $validated['location_name'],
            'city' => $validated['city'] ?? $request->user()->city,
            'start_time' => $validated['start_time'],
            'max_participants' => $validated['max_participants'] ?? 50,
            'status' => 'upcoming',
        ]);

        // Auto join creator as participant
        $event->participants()->attach($request->user()->id);

        return response()->json([
            'message' => 'Event created successfully',
            'event' => $event->load(['creator', 'participants'])->loadCount('participants'),
        ], 201);
    }

    /**
     * Event Detail (Step 8).
     */
    public function show(Request $request, $id)
    {
        $event = Event::with(['creator', 'participants'])
            ->withCount('participants')
            ->findOrFail($id);

        $userId = $request->user()?->id;
        $event->is_joined = $userId ? $event->participants->contains('id', $userId) : false;

        return response()->json([
            'event' => $event,
        ]);
    }

    /**
     * Toggle "I'm In" (join/leave event).
     */
    public function toggleJoin(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        $user = $request->user();

        if ($event->participants()->where('user_id', $user->id)->exists()) {
            $event->participants()->detach($user->id);
            $joined = false;
            $message = "You have left the event.";
        } else {
            // Check capacity
            $currentCount = $event->participants()->count();
            if ($currentCount >= $event->max_participants) {
                return response()->json(['message' => 'Event is already full!'], 400);
            }

            $event->participants()->attach($user->id);
            $joined = true;
            $message = "You're in! See you there.";
        }

        $participantsCount = $event->participants()->count();

        return response()->json([
            'message' => $message,
            'is_joined' => $joined,
            'participants_count' => $participantsCount,
        ]);
    }
}
