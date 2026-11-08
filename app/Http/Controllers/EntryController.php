<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EntryController extends Controller
{
    public function index(Request $request)
    {
        $query = auth()->user()->entries()->with('tags')->latest();

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        // Filter mood
        if ($request->filled('mood')) {
            $query->where('mood', $request->mood);
        }

        // Filter tag
        if ($request->filled('tag')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('name', $request->tag);
            });
        }

        // Filter by date (dari klik kalender)
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // Sorting
        $sort = $request->get('sort', 'latest');
        if ($sort === 'oldest') {
            $query->oldest();
        } elseif ($sort === 'title') {
            $query->orderBy('title');
        } else {
            $query->latest();
        }

        $entries = $query->paginate(9)->withQueryString();
        $tags = Tag::all();

        return view('entries.index', compact('entries', 'tags'));
    }

    public function create()
    {
        $tags = Tag::all();
        return view('entries.create', compact('tags'));
    }

    public function store(Request $request)
{
    $request->validate([
        'title'   => 'required|string|max:255',
        'content' => 'required|string',
        'mood'    => 'nullable|string',
        'photo'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'tags'    => 'nullable|array',
        'tags.*'  => 'string|max:50',
    ]);

    $photoPath = null;
    if ($request->hasFile('photo')) {
        $photoPath = $request->file('photo')->store('entries', 'public');
    }

    $entry = auth()->user()->entries()->create([
        'title'   => $request->title,
        'content' => $request->content,
        'mood'    => $request->mood,
        'photo'   => $photoPath,
    ]);

    if ($request->filled('tags')) {
        $tagIds = [];
        foreach ($request->tags as $tagName) {
            $tag = Tag::firstOrCreate(['name' => strtolower(trim($tagName))]);
            $tagIds[] = $tag->id;
        }
        $entry->tags()->sync($tagIds);
    }

    return redirect()->route('entries.show', $entry)->with('success', 'Entri berhasil disimpan!');
}

    public function show(Entry $entry)
    {
        abort_if($entry->user_id !== auth()->id(), 403);
        $entry->load('tags');
        return view('entries.show', compact('entry'));
    }

    public function edit(Entry $entry)
    {
        abort_if($entry->user_id !== auth()->id(), 403);
        $tags = Tag::all();
        $entry->load('tags');
        return view('entries.edit', compact('entry', 'tags'));
    }

    public function update(Request $request, Entry $entry)
    {
        abort_if($entry->user_id !== auth()->id(), 403);

        $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'mood'    => 'nullable|string',
            'photo'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'tags'    => 'nullable|array',
            'tags.*'  => 'string|max:50',
        ]);

        if ($request->hasFile('photo')) {
            if ($entry->photo) {
                Storage::disk('public')->delete($entry->photo);
            }
            $entry->photo = $request->file('photo')->store('entries', 'public');
        }

        $entry->update([
            'title'   => $request->title,
            'content' => $request->content,
            'mood'    => $request->mood,
            'photo'   => $entry->photo,
        ]);

        if ($request->filled('tags')) {
            $tagIds = [];
            foreach ($request->tags as $tagName) {
                $tag = Tag::firstOrCreate(['name' => strtolower(trim($tagName))]);
                $tagIds[] = $tag->id;
            }
            $entry->tags()->sync($tagIds);
        } else {
            $entry->tags()->detach();
        }

        return redirect()->route('entries.show', $entry)
            ->with('success', 'Entri berhasil diperbarui!');
    }

    public function destroy(Entry $entry)
    {
        abort_if($entry->user_id !== auth()->id(), 403);

        if ($entry->photo) {
            Storage::disk('public')->delete($entry->photo);
        }

        $entry->delete();

        return redirect()->route('entries.index')
            ->with('success', 'Entri berhasil dihapus!');
    }
    public function uploadImage(Request $request)
    {
        $request->validate([
        'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

    $path = $request->file('image')->store('entries/inline', 'public');

    return response()->json(['url' => url('storage/' . $path)]);
    }
}