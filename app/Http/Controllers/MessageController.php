<?php

namespace App\Http\Controllers;

use App\Models\Contact;

class MessageController extends Controller
{
    public function index()
    {
        $messages = Contact::latest()->paginate(15);

        return view('admin.messages.index', compact('messages'));
    }

    public function markRead(Contact $message)
    {
        $message->update(['read_at' => now()]);

        return redirect()->route('admin.messages')->with('success', 'Message marked as read.');
    }

    public function destroy(Contact $message)
    {
        $message->delete();

        return redirect()->route('admin.messages')->with('success', 'Message deleted.');
    }
}
