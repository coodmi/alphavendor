<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BooksHeroSection;
use Illuminate\Http\Request;

class BooksHeroController extends Controller
{
    public function edit()
    {
        $hero = BooksHeroSection::first();

        return view('admin.books.hero.edit', compact('hero'));
    }

    public function update(Request $request)
    {
        $hero = BooksHeroSection::first();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'rating_score' => 'required|numeric|min:0|max:5',
            'rating_count' => 'required|integer|min:0',
            'fsc_text' => 'required|string',
            'slider_images' => 'required|array|min:1',
            'slider_images.*' => 'required|url',
        ]);

        $hero->update($validated);

        return back()->with('success', 'Books Hero Section updated successfully!');
    }
}
