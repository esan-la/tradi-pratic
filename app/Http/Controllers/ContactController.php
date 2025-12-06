<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function index()
    {
        $contactInfo = [
            'phone' => '+226 XX XX XX XX',
            'whatsapp' => '+226 XX XX XX XX',
            'email' => 'contact@adjaamsetou.com',
            'address' => 'Komsilga, Burkina Faso',
            'maps_embed' => 'https://www.google.com/maps/embed?pb=...' // Coordonnées GPS
        ];

        return view('contact', compact('contactInfo'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Contact::create($request->all());

        // Envoyer notification email
        // Mail::to('contact@adjaamsetou.com')->send(new ContactMessage($request->all()));

        return redirect()->route('contact')
            ->with('success', 'Votre message a été envoyé avec succès. Nous vous répondrons dans les plus brefs délais.');
    }
}
