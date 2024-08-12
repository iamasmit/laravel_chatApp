<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $user = $request->user();
        $conversations = $user->conversations();
        $conversations->load(['users' => function($query) use($user){
            $query->where('user_id', '!=', $user->id);
        }]);
        return response()->json($conversations);
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);
        $user = $request->user();
        $conversation = Conversation::create();
        $conversation->users()->attach($request->user_id);
        $conversation->load(['users' => function($query) use($user){
            $query->where('user_id', '!=', $user->id);
        }]);
        return response()->json($conversation);

    }

    /**
     * Display the specified resource.
     */
    public function show(Conversation $conversation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Conversation $conversation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Conversation $conversation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Conversation $conversation)
    {
        //
    }
}
