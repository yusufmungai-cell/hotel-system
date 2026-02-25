<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MenuCategory;

class MenuCategoryController extends Controller
{
    public function index()
    {
        $categories = MenuCategory::latest()->get();
        return view('menu_categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:menu_categories,name'
        ]);

        MenuCategory::create([
            'name' => $request->name
        ]);

        return back()->with('success', 'Category added successfully.');
    }

    public function update(Request $request, $id)
    {
        $category = MenuCategory::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:menu_categories,name,' . $id
        ]);

        $category->update([
            'name' => $request->name
        ]);

        return back()->with('success', 'Category updated successfully.');
    }

    public function destroy($id)
    {
        $category = MenuCategory::findOrFail($id);

        // Prevent delete if linked to menu items
        if ($category->items()->count() > 0) {
            return back()->with('error', 'Cannot delete category with menu items.');
        }

        $category->delete();

        return back()->with('success', 'Category deleted successfully.');
    }
}
