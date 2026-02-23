<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SocialLink;
use Illuminate\Http\Request;

class SocialLinkController extends Controller
{
    public function index()
    {
        $socialLinks = SocialLink::ordered()->get();
        return view('admin.social-links.index', compact('socialLinks'));
    }

    public function create()
    {
        $platforms = $this->getPlatforms();
        return view('admin.social-links.create', compact('platforms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'platform' => 'required|string|max:255',
            'url' => 'required|url',
            'icon' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $validated['order'] ?? SocialLink::max('order') + 1;

        SocialLink::create($validated);

        return redirect()->route('admin.social-links.index')
            ->with('success', 'Lien ajouté avec succès.');
    }

    public function edit(SocialLink $socialLink)
    {
        $platforms = $this->getPlatforms();
        return view('admin.social-links.edit', compact('socialLink', 'platforms'));
    }

    public function update(Request $request, SocialLink $socialLink)
    {
        $validated = $request->validate([
            'platform' => 'required|string|max:255',
            'url' => 'required|url',
            'icon' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $socialLink->update($validated);

        return redirect()->route('admin.social-links.index')
            ->with('success', 'Lien mis à jour avec succès.');
    }

    public function destroy(SocialLink $socialLink)
    {
        $socialLink->delete();

        return redirect()->route('admin.social-links.index')
            ->with('success', 'Lien supprimé avec succès.');
    }

    public function toggle(SocialLink $socialLink)
    {
        $socialLink->update(['is_active' => !$socialLink->is_active]);

        return back()->with('success', 'Statut mis à jour.');
    }

    public function updateOrder(Request $request)
    {
        $order = $request->input('order', []);

        foreach ($order as $index => $id) {
            SocialLink::where('id', $id)->update(['order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }

    private function getPlatforms()
    {
        return [
            'Facebook' => 'facebook',
            'Twitter / X' => 'twitter',
            'Instagram' => 'instagram',
            'LinkedIn' => 'linkedin',
            'YouTube' => 'youtube',
            'WhatsApp' => 'whatsapp',
            'TikTok' => 'tiktok',
            'Telegram' => 'telegram',
            'Pinterest' => 'pinterest',
            'Snapchat' => 'snapchat',
        ];
    }
}
