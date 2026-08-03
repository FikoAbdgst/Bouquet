<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategoryField;
use App\Models\CategoryFieldOption;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products', 'fields')->latest()->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
            'fields' => ['nullable', 'array'],
            'fields.*.label' => ['required', 'string', 'max:255'],
            'fields.*.type' => ['required', 'in:text,select,checkbox,file'],
            'fields.*.options' => ['nullable', 'array'],
            'fields.*.options.*.name' => ['required', 'string', 'max:255'],
            'fields.*.options.*.price' => ['nullable', 'integer', 'min:0'],
            'fields.*.is_required' => ['nullable', 'boolean'],
        ]);

        $category = Category::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        if (! empty($validated['fields'])) {
            $this->syncFields($category, $validated['fields']);
        }

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category)
    {
        $category->load('fields.fieldOptions');

        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name,'.$category->id],
            'fields' => ['nullable', 'array'],
            'fields.*.label' => ['required', 'string', 'max:255'],
            'fields.*.type' => ['required', 'in:text,select,checkbox,file'],
            'fields.*.options' => ['nullable', 'array'],
            'fields.*.options.*.name' => ['required', 'string', 'max:255'],
            'fields.*.options.*.price' => ['nullable', 'integer', 'min:0'],
            'fields.*.is_required' => ['nullable', 'boolean'],
        ]);

        $category->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        $this->syncFields($category, $validated['fields'] ?? []);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Request $request, Category $category)
    {
        $coreCategories = ['Fresh Flower', 'Artificial Flower', 'Thumbelina Bouquet', 'Buket Uang'];

        if (in_array($category->name, $coreCategories)) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Kategori "'.$category->name.'" adalah kategori inti dan tidak dapat dihapus.');
        }

        $productsCount = $category->products()->count();

        if ($productsCount > 0) {
            $request->validate([
                'agree' => ['accepted'],
            ]);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }

    private function syncFields(Category $category, array $fields): void
    {
        $keepIds = [];

        foreach ($fields as $fieldData) {
            $fieldId = $fieldData['id'] ?? null;

            $data = [
                'label' => $fieldData['label'],
                'type' => $fieldData['type'],
                'options' => null,
                'is_required' => ! empty($fieldData['is_required']),
            ];

            if ($fieldId && $field = $category->fields()->find($fieldId)) {
                $field->update($data);
                $keepIds[] = $field->id;
            } else {
                $field = $category->fields()->create($data);
                $keepIds[] = $field->id;
            }

            if (in_array($fieldData['type'], ['select', 'checkbox']) && !empty($fieldData['options'])) {
                $this->syncFieldOptions($field, $fieldData['options']);
            } else {
                $field->fieldOptions()->delete();
            }
        }

        $category->fields()->whereNotIn('id', $keepIds)->delete();
    }

    private function syncFieldOptions(CategoryField $field, array $options): void
    {
        $keepOptionIds = [];

        foreach ($options as $optData) {
            if (empty($optData['name'])) continue;

            $optId = $optData['id'] ?? null;
            $optPayload = [
                'name' => $optData['name'],
                'price' => (int) ($optData['price'] ?? 0),
            ];

            if ($optId && $option = $field->fieldOptions()->find($optId)) {
                $option->update($optPayload);
                $keepOptionIds[] = $option->id;
            } else {
                $option = $field->fieldOptions()->create($optPayload);
                $keepOptionIds[] = $option->id;
            }
        }

        $field->fieldOptions()->whereNotIn('id', $keepOptionIds)->delete();
    }
}
