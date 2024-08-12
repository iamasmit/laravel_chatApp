<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function usersearch(Request $request)
    {
        // Validate the input
        $request->validate([
            'query' => 'required|string',
        ]);

        // Get the search query
        $query = $request->input('query');

        // Ensure the user is authenticated
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Perform the search
        $users = User::where('name', 'like', '%' . $query . '%')
                     ->where('id', '!=', Auth::id())
                     ->get();

        // Return the search results as JSON
        return response()->json($users);
    }
}

