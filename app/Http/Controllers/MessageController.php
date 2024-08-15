<?php

namespace App\Http\Controllers;

use App\Events\MessageSeen;
use App\Models\Message;
use App\Models\Conversation;
use Illuminate\Http\Request;
use App\Events\MessageSent;


class MessageController extends Controller
{
    //
    public function index(Conversation $conversation){
        $messages =$conversation->messages()->with('user:id,name,is_online,last_online_at')->orderBy('created_at', 'asc')->get();
       

        return response()->json($messages);
    }

      public function store(Request $request, Conversation $conversation){
        $request->validate([
            'test' => 'required'
        ]);

        $message = $conversation->messages()->create([
            'test' =>$request->test,
            'user_id' =>$request->user()->id(),
        ]);

        broadcast(new MessageSent($message))->toOthers();

        $conversation->update([
            'last_message'=>$message->text,
            'last_message_at'=>$message->created_at,
            'user_id' => $request->user()->id           
         ]);
         return response()->json($message);
       
    }


    public function markSeen(Message $message){
        $message->update([
            'seen_at' => now()
            ]);
            return response()->json(['$message' => 'seen']);
    }
    public function markAsSeen(Request $request, Conversation $conversation)
    {
        $user = $request->user();

        $messages = $conversation->messages()
        ->where('user_id', '!=', $user->id)
        ->where('seen', false)
        ->get();

        foreach ($messages as $message){
            $message->update(['seen' =>true
            ]);
            broadcast(new MessageSeen($message))->toOthers();
        }
        return response()->json(['success' => true]);
    }

}

