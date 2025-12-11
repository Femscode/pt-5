<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Conversation;
use App\Models\User;

Broadcast::channel('conversation.{id}', function ($user, $id) {
    return Conversation::where('id', (int)$id)
        ->whereHas('participants', function ($q) use ($user) {
            $q->where('users.id', $user->id);
        })
        ->exists();
});

Broadcast::channel('conversation.{uuid}', function ($user, $uuid) {
    return Conversation::where('uuid', (string)$uuid)
        ->whereHas('participants', function ($q) use ($user) {
            $q->where('users.id', $user->id);
        })
        ->exists();
});

Broadcast::channel('inbox.{uuid}', function ($user, $uuid) {
    return (string)($user->uuid ?? '') === (string)$uuid;
});

