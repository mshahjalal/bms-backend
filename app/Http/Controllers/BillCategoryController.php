<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBillCategoryRequest;
use App\Http\Requests\UpdateBillCategoryRequest;
use App\Models\BillCategory;
use App\Services\BillCategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class BillCategoryController extends Controller
{
    public function __construct(protected BillCategoryService $categoryService)
    {
    }

    public function index()
    {
        Gate::authorize('viewAny', BillCategory::class);
        return response()->json(BillCategory::all());
    }

    public function store(StoreBillCategoryRequest $request)
    {
        Gate::authorize('create', BillCategory::class);

        $category = $this->categoryService->createCategory($request->validated());

        return response()->json($category, 201);
    }

    public function show(BillCategory $category)
    {
        Gate::authorize('view', $category);
        return response()->json($category);
    }

    public function update(UpdateBillCategoryRequest $request, BillCategory $category)
    {
        Gate::authorize('update', $category);

        $category = $this->categoryService->updateCategory($category, $request->validated());

        return response()->json($category);
    }

    public function destroy(BillCategory $category)
    {
        Gate::authorize('delete', $category);
        $this->categoryService->deleteCategory($category);
        return response()->json(null, 204);
    }
}
