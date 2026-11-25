<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * List user conversations (Chats screen - Step 7).
     */
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        $conversations = Conversation::whereHas('participants', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })
        ->with(['participants' => function ($q) use ($userId) {
            $q->where('user_id', '!=', $userId);
        }, 'lastMessage.sender'])
        ->withCount(['messages as unread_count' => function ($q) use ($userId) {
            $q->where('sender_id', '!=', $userId)->where('is_read', false);
        }])
        ->orderByDesc('last_message_at')
        ->get();

        // Format direct conversation titles & avatars if not explicitly set
        $conversations->transform(function ($conv) {
            if ($conv->type === 'direct' && $conv->participants->isNotEmpty()) {
                $otherUser = $conv->participants->first();
                $conv->display_title = $otherUser->name;
                $conv->display_avatar = $otherUser->avatar_url;
            } else {
                $conv->display_title = $conv->title ?? 'Group Chat';
                $conv->display_avatar = $conv->avatar_url;
            }
            return $conv;
        });

        return response()->json([
            'conversations' => $conversations,
        ]);
    }

    /**
     * Create or get direct conversation with a user.
     */
    public function startDirectChat(Request $request)
    {
        $validated = $request->validate([
            'recipient_id' => 'required|exists:users,id|different:' . $request->user()->id,
        ]);

        $userId = $request->user()->id;
        $recipientId = $validated['recipient_id'];

        // Find existing direct conversation
        $conversation = Conversation::where('type', 'direct')
            ->whereHas('participants', fn($q) => $q->where('user_id', $userId))
            ->whereHas('participants', fn($q) => $q->where('user_id', $recipientId))
            ->first();

        if (! $conversation) {
            $conversation = Conversation::create([
                'type' => 'direct',
                'last_message_at' => now(),
            ]);

            $conversation->participants()->attach([$userId, $recipientId]);
        }

        return response()->json([
            'conversation' => $conversation->load('participants'),
        ]);
    }

    /**
     * Get messages in a conversation.
     */
    public function messages(Request $request, $id)
    {
        $userId = $request->user()->id;

        $conversation = Conversation::whereHas('participants', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->findOrFail($id);

        // Mark incoming messages as read
        Message::where('conversation_id', $conversation->id)
            ->where('sender_id', '!=', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = $conversation->messages()
            ->with('sender:id,name,avatar_url')
            ->orderBy('created_at', 'asc')
            ->paginate(50);

        return response()->json($messages);
    }

    /**
     * Send message in a conversation.
     */
    public function sendMessage(Request $request, $id)
    {
        $userId = $request->user()->id;

        $conversation = Conversation::whereHas('participants', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->findOrFail($id);

        $validated = $request->validate([
            'message' => 'required|string',
        ]);

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $userId,
            'message' => $validated['message'],
            'is_read' => false,
        ]);

        $conversation->update([
            'last_message_at' => now(),
        ]);

        return response()->json([
            'message' => $message->load('sender:id,name,avatar_url'),
        ], 201);
    }
}
