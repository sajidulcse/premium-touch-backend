<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    private function generateUniqueSlug($name, $ignoreId = null)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        while (Category::where('slug', $slug)->where('id', '!=', $ignoreId)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        return $slug;
    }
    public function apiIndex()
    {
        return Category::where('parent_id', 0)
            ->with([
                'children' => function ($query) {
                    $query->with('children')->orderBy('position');
                }
            ])
            ->where('status', 1)
            ->orderBy('position')
            ->get();
    }

    public function index()
    {
        return response()->json(
            Category::where('parent_id', 0)
                ->with([
                    'children' => function ($query) {
                        $query->with('children')->orderBy('position');
                    }
                ])
                ->orderBy('position')
                ->get()
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|integer',
            'status' => 'boolean',
            'position' => 'nullable|integer'
        ]);

        $category = Category::create([
            'name' => $request->name,
            'slug' => $this->generateUniqueSlug($request->name),
            'parent_id' => $request->parent_id ?? 0,
            'status' => $request->status ?? 1,
            'position' => $request->position ?? 0
        ]);

        return response()->json(['message' => 'Category created', 'category' => $category]);
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'string|max:255',
            'parent_id' => 'nullable|integer',
            'status' => 'boolean',
            'position' => 'nullable|integer'
        ]);

        if ($request->has('name')) {
            $category->name = $request->name;
            $category->slug = $this->generateUniqueSlug($request->name, $id);
        }

        if ($request->has('parent_id'))
            $category->parent_id = $request->parent_id;
        if ($request->has('status'))
            $category->status = $request->status;
        if ($request->has('position'))
            $category->position = $request->position;

        $category->save();

        return response()->json(['message' => 'Category updated', 'category' => $category]);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        // Also delete children (optional, or set parent_id to 0)
        // For simplicity, let's just delete the category.
        // If it has children, you might want to prevent deletion or delete them too.
        Category::where('parent_id', $category->id)->delete();

        $category->delete();
        return response()->json(['message' => 'Category and its subcategories deleted']);
    }
}
