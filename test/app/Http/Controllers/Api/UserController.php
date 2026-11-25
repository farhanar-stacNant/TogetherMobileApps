<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Get nearby / recommended people for the Home Feed.
     */
    public function nearby(Request $request)
    {
        $currentUser = $request->user();
        
        $query = User::where('id', '!=', $currentUser->id);

        if ($currentUser->city) {
            // Prioritize people in the same city if available
            $query->orderByRaw("CASE WHEN city = ? THEN 0 ELSE 1 END", [$currentUser->city]);
        }

        $users = $query->take(15)->get();

        return response()->json([
            'users' => $users,
        ]);
    }

    /**
     * Search / list people (Explore tab).
     */
    public function index(Request $request)
    {
        $currentUser = $request->user();
        $query = User::where('id', '!=', $currentUser->id);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('bio', 'like', "%{$search}%");
            });
        }

        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        $users = $query->paginate(20);

        return response()->json($users);
    }
}
