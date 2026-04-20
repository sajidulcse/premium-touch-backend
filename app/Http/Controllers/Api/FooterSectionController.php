<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\FooterSection;
use Illuminate\Http\Request;

class FooterSectionController extends Controller
{
    // Get all active footer sections
    public function index()
    {
        $sections = FooterSection::where('status', 1)
                    ->orderBy('display_order', 'asc')
                    ->get();

        return response()->json($sections);
    }

    // Get a single section
    public function show($id)
    {
        $section = FooterSection::findOrFail($id);
        return response()->json($section);
    }

    // Create a new section
    public function store(Request $request)
    {
        $request->validate([
            'section_title' => 'required|string|max:255',
            'section_type' => 'required|in:text,links,social,newsletter',
            'content' => 'nullable',
            'display_order' => 'nullable|integer',
            'status' => 'nullable|boolean',
        ]);

        $section = FooterSection::create($request->all());
        return response()->json($section, 201);
    }

    // Update a section
    public function update(Request $request, $id)
    {
        $section = FooterSection::findOrFail($id);
        $section->update($request->all());

        return response()->json($section);
    }

    // Delete a section
    public function destroy($id)
    {
        $section = FooterSection::findOrFail($id);
        $section->delete();

        return response()->json(['message' => 'Section deleted successfully']);
    }
}
