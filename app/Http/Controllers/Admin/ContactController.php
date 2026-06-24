<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Contact::query();

        // Filtre par statut
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Recherche
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $contacts = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total' => Contact::count(),
            'new' => Contact::where('status', 'new')->count(),
            'read' => Contact::where('status', 'read')->count(),
            'replied' => Contact::where('status', 'replied')->count(),
        ];

        return view('admin.contacts.index', compact('contacts', 'stats'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact)
    {
        // Marquer comme lu
        if ($contact->status === 'new') {
            $contact->update(['status' => 'read']);
        }

        return view('admin.contacts.show', compact('contact'));
    }

    /**
     * Reply to a contact
     */
    public function reply(Request $request, Contact $contact)
    {
        $validated = $request->validate([
            'reply_message' => 'required|string',
        ]);

        // Envoyer l'email (configuration à adapter)
        try {
            // Mail::to($contact->email)->send(new ContactReply($contact, $validated['reply_message']));

            // Marquer comme répondu et ajouter la note
            $contact->update([
                'status' => 'replied',
                'admin_notes' => ($contact->admin_notes ? $contact->admin_notes . "\n\n" : '') .
                                 "Réponse envoyée le " . now()->format('d/m/Y H:i') . " par " . auth()->user()->name . ":\n" .
                                 $validated['reply_message'],
            ]);

            activity()
                ->performedOn($contact)
                ->causedBy(auth()->user())
                ->log('Réponse envoyée au message de ' . $contact->name);

            return back()->with('success', 'Réponse envoyée avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'envoi de la réponse: ' . $e->getMessage());
        }
    }

    /**
     * Add admin notes
     */
    public function addNote(Request $request, Contact $contact)
    {
        $validated = $request->validate([
            'note' => 'required|string',
        ]);

        $newNote = "Note ajoutée le " . now()->format('d/m/Y H:i') . " par " . auth()->user()->name . ":\n" .
                   $validated['note'];

        $contact->update([
            'admin_notes' => ($contact->admin_notes ? $contact->admin_notes . "\n\n" : '') . $newNote,
        ]);

        return back()->with('success', 'Note ajoutée avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        $name = $contact->name;
        $contact->delete();

        activity()
            ->causedBy(auth()->user())
            ->log('Suppression du message de ' . $name);

        return redirect()->route('admin.contacts.index')
            ->with('success', 'Message supprimé avec succès.');
    }

    /**
     * Bulk actions
     */
    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:mark_read,mark_replied,delete',
            'contacts' => 'required|array',
            'contacts.*' => 'exists:contacts,id',
        ]);

        $count = 0;

        switch ($validated['action']) {
            case 'mark_read':
                Contact::whereIn('id', $validated['contacts'])->update(['status' => 'read']);
                $count = count($validated['contacts']);
                $message = "$count message(s) marqué(s) comme lu(s).";
                break;

            case 'mark_replied':
                Contact::whereIn('id', $validated['contacts'])->update(['status' => 'replied']);
                $count = count($validated['contacts']);
                $message = "$count message(s) marqué(s) comme répondu(s).";
                break;

            case 'delete':
                Contact::whereIn('id', $validated['contacts'])->delete();
                $count = count($validated['contacts']);
                $message = "$count message(s) supprimé(s).";
                break;
        }

        activity()
            ->causedBy(auth()->user())
            ->log("Action groupée sur les contacts: {$validated['action']} ($count messages)");

        return back()->with('success', $message);
    }
}
