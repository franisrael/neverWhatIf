<?php

namespace App\Http\Controllers;

use App\Mail\ContactReceived;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::all();
        return response($contacts);
    }

    public function show(Contact $contact)
    {
        return response($contact);
    }

    public function store(Request $request)
    {
        $request->validate(['name' => ['required']]);
        $request->validate(['email' => ['required']]);
        $request->validate(['message' => ['required']]);

        $contact = Contact::create($request->all());

        if ($contact) {
            $this->sendEmail($contact);
        }

        return $contact;
    }

    public function sendEmail(Contact $contact)
    {
        $details = [
            'title' => 'Mail from neverWhatIf',
            'body' => "Thank you {$contact->name} for contacting us; we will respond as soon as possible."
        ];
        Mail::to($contact->email)->send(new ContactReceived($details));
    }
}
