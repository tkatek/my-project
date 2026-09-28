<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:2|max:100',
            'email' => 'required|email|max:160',
            'phone' => 'nullable|string|max:25',
            'subject' => 'required|in:experience,private,group,question',
            'message' => 'required|string|min:10|max:2000',
            'consent' => 'accepted',
            'company_website' => 'nullable|max:0',
            'request_id' => 'nullable|string|max:100',
        ]);

        $requestId = $validated['request_id'] ?? $request->header('Idempotency-Key');

        if ($requestId && Contact::where('request_id', $requestId)->exists()) {
            return response()->json(['success' => true, 'reference' => 'MSG-'.str_pad(Contact::where('request_id', $requestId)->first()->id, 4, '0', STR_PAD_LEFT)]);
        }

        $contact = Contact::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'consent' => true,
            'request_id' => $requestId,
        ]);

        return response()->json([
            'success' => true,
            'reference' => 'MSG-'.str_pad($contact->id, 4, '0', STR_PAD_LEFT),
        ]);
    }
}
