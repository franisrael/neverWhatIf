<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

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

        return Contact::create($request->all());
    }
}
