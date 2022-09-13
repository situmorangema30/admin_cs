<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductCategory;

class ProductCategoryController extends Controller
{
    public function create()
    {
        return view('product_categories.form', [
            'name' => 'product_category',
            'menu' => 'create',
            'title' => 'Create Product Category',
            'formAction' => 'store',
            'formInputs' => [
                [
                    'type' => 'text',
                    'name' => 'category',
                    'label' => 'Category',
                ],
                [
                    'type' => 'textarea',
                    'name' => 'description',
                    'label' => 'Description',
                ],
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'bail|required|max:100',
            'description' => 'nullable',
        ]);

        $productCategory = ProductCategory::create($validated);

        return redirect('/product-categories');
    }

    public function data()
    {
        return view('product_categories.data', [
            'name' => 'product_category',
            'menu' => 'data',
            'title' => 'Table Product Category',
            'table' => [
                'title' => [
                    'Category',
                    'Description'
                ],
                'size' => [3, 8, 1,],
                'data' => ProductCategory::all(),

            ],
        ]);
    }

    public function edit(ProductCategory $productCategory)
    {
        return view('product_categories.form', [
            'name' => 'product_category',
            'menu' => 'edit',
            'title' => 'Edit Product Category',
            'formAction' => 'update',
            'formInputs' => [
                [
                    'type' => 'text',
                    'name' => 'category',
                    'label' => 'Category',
                ],
                [
                    'type' => 'textarea',
                    'name' => 'description',
                    'label' => 'Description',
                ],
            ],
            'productCategory' => $productCategory,
        ]);
    }

    public function update(Request $request, ProductCategory $productCategory)
    {
        $validated = $request->validate([
            'category' => 'bail|required|max:100',
            'description' => 'nullable',
        ]);

        ProductCategory::where('id', $productCategory->id)->update($validated);

        return redirect('/product-categories');
    }

    public function delete(Request $request, ProductCategory $productCategory)
    {
        ProductCategory::destroy($productCategory->id);

        return redirect('/product-categories');
    }
}
