<?php

namespace App\Services;

use App\Models\BillCategory;

class BillCategoryService
{
    public function createCategory(array $data): BillCategory
    {
        return BillCategory::create($data);
    }

    public function updateCategory(BillCategory $category, array $data): BillCategory
    {
        $category->update($data);
        return $category;
    }

    public function deleteCategory(BillCategory $category): void
    {
        $category->delete();
    }
}
